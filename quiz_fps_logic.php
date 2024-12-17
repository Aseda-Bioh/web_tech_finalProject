<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    die(json_encode([
        'success' => false,
        'message' => 'Please log in to take the quiz',
        'redirect' => 'login.php'
    ]));
}

// Database connection
define('DB_HOST', 'localhost');
define('DB_USER', 'kwabena.bioh');
define('DB_PASS', 'FatherAbraham2');
define('DB_NAME', 'webtech_fall2024_kwabena_bioh');

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die(json_encode([
        'success' => false,
        'message' => 'Database connection failed'
    ]));
}

// Handle different AJAX requests
$action = $_POST['action'] ?? '';

switch ($action) {
    case 'start_quiz':
        startQuiz($conn);
        break;
    case 'get_question':
        getQuestion($conn);
        break;
    case 'submit_answer':
        submitAnswer($conn);
        break;
    case 'end_quiz':
        endQuiz($conn);
        break;
    default:
        die(json_encode([
            'success' => false,
            'message' => 'Invalid action'
        ]));
}

function startQuiz($conn) {
    $categoryId = 3; // FPS category
    $userId = $_SESSION['user_id'];

    // Begin transaction
    $conn->begin_transaction();

    try {
        // Check for any ongoing quiz sessions
        $checkSession = $conn->prepare("
            SELECT session_id FROM quiz_sessions 
            WHERE user_id = ? AND category_id = ? AND status = 'In Progress'
        ");
        $checkSession->bind_param('ii', $userId, $categoryId);
        $checkSession->execute();
        $result = $checkSession->get_result();

        if ($result->num_rows > 0) {
            // Resume existing session
            $session = $result->fetch_assoc();
            $sessionId = $session['session_id'];
        } else {
            // Start new session
            $startSession = $conn->prepare("
                INSERT INTO quiz_sessions (user_id, category_id)
                VALUES (?, ?)
            ");
            $startSession->bind_param('ii', $userId, $categoryId);
            $startSession->execute();
            $sessionId = $conn->insert_id;

            // Log activity
            $activityStmt = $conn->prepare("
                INSERT INTO activity_log (user_id, activity_type, category_id, description)
                VALUES (?, 'Quiz_Completed', ?, 'Started FPS quiz')
            ");
            $activityStmt->bind_param('ii', $userId, $categoryId);
            $activityStmt->execute();
        }

        $conn->commit();

        echo json_encode([
            'success' => true,
            'session_id' => $sessionId
        ]);
    } catch (Exception $e) {
        $conn->rollback();
        die(json_encode([
            'success' => false,
            'message' => 'Failed to start quiz: ' . $e->getMessage()
        ]));
    }
}

function getQuestion($conn) {
    $sessionId = $_POST['session_id'] ?? 0;
    $userId = $_SESSION['user_id'];
    $categoryId = 3; // FPS category

    // Verify session belongs to user
    $verifySession = $conn->prepare("
        SELECT session_id FROM quiz_sessions 
        WHERE session_id = ? AND user_id = ? AND status = 'In Progress'
    ");
    $verifySession->bind_param('ii', $sessionId, $userId);
    $verifySession->execute();
    if ($verifySession->get_result()->num_rows === 0) {
        die(json_encode([
            'success' => false,
            'message' => 'Invalid session'
        ]));
    }

    // Get answered questions in this session
    $answeredQuery = $conn->prepare("
        SELECT question_id FROM user_answers
        WHERE session_id = ?
    ");
    $answeredQuery->bind_param('i', $sessionId);
    $answeredQuery->execute();
    $result = $answeredQuery->get_result();
    $answeredQuestions = [];
    while ($row = $result->fetch_assoc()) {
        $answeredQuestions[] = $row['question_id'];
    }

    // Get next random question
    $questionQuery = $conn->prepare("
        SELECT q.question_id, q.question_text, q.points, q.time_limit
        FROM questions q
        WHERE q.category_id = ? AND q.is_active = 1
        " . (empty($answeredQuestions) ? "" : "AND q.question_id NOT IN (" . implode(',', $answeredQuestions) . ")") . "
        ORDER BY RAND()
        LIMIT 1
    ");
    $questionQuery->bind_param('i', $categoryId);
    $questionQuery->execute();
    $question = $questionQuery->get_result()->fetch_assoc();

    if (!$question) {
        die(json_encode([
            'success' => false,
            'message' => 'No more questions available',
            'quiz_complete' => true
        ]));
    }

    // Get answers for this question
    $answersQuery = $conn->prepare("
        SELECT answer_id, answer_text
        FROM answers
        WHERE question_id = ?
        ORDER BY RAND()
    ");
    $answersQuery->bind_param('i', $question['question_id']);
    $answersQuery->execute();
    $answers = $answersQuery->get_result()->fetch_all(MYSQLI_ASSOC);

    echo json_encode([
        'success' => true,
        'question' => [
            'id' => $question['question_id'],
            'text' => $question['question_text'],
            'points' => $question['points'],
            'time_limit' => $question['time_limit'],
            'answers' => $answers
        ]
    ]);
}

function submitAnswer($conn) {
    $sessionId = $_POST['session_id'] ?? 0;
    $questionId = $_POST['question_id'] ?? 0;
    $answerId = $_POST['answer_id'] ?? 0;
    $timeTaken = $_POST['time_taken'] ?? 0;
    $userId = $_SESSION['user_id'];

    $conn->begin_transaction();

    try {
        // Verify correct answer and get points
        $verifyQuery = $conn->prepare("
            SELECT a.is_correct, q.points
            FROM answers a
            JOIN questions q ON a.question_id = q.question_id
            WHERE a.answer_id = ? AND q.question_id = ?
        ");
        $verifyQuery->bind_param('ii', $answerId, $questionId);
        $verifyQuery->execute();
        $result = $verifyQuery->get_result()->fetch_assoc();

        $isCorrect = $result['is_correct'];
        $pointsEarned = $isCorrect ? $result['points'] : 0;

        // Record answer
        $recordAnswer = $conn->prepare("
            INSERT INTO user_answers 
            (session_id, question_id, selected_answer_id, time_taken, is_correct, points_earned)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $recordAnswer->bind_param('iiiiii', $sessionId, $questionId, $answerId, $timeTaken, $isCorrect, $pointsEarned);
        $recordAnswer->execute();

        // Update session stats
        $updateSession = $conn->prepare("
            UPDATE quiz_sessions 
            SET questions_answered = questions_answered + 1,
                correct_answers = correct_answers + ?,
                total_score = total_score + ?,
                accuracy_percentage = (correct_answers * 100.0) / questions_answered
            WHERE session_id = ?
        ");
        $correct = $isCorrect ? 1 : 0;
        $updateSession->bind_param('iii', $correct, $pointsEarned, $sessionId);
        $updateSession->execute();

        $conn->commit();

        echo json_encode([
            'success' => true,
            'correct' => $isCorrect,
            'points_earned' => $pointsEarned
        ]);
    } catch (Exception $e) {
        $conn->rollback();
        die(json_encode([
            'success' => false,
            'message' => 'Failed to submit answer: ' . $e->getMessage()
        ]));
    }
}

function endQuiz($conn) {
    $sessionId = $_POST['session_id'] ?? 0;
    $userId = $_SESSION['user_id'];
    $categoryId = 3; // FPS category

    $conn->begin_transaction();

    try {
        // Get session stats
        $statsQuery = $conn->prepare("
            SELECT total_score, accuracy_percentage, questions_answered, correct_answers
            FROM quiz_sessions
            WHERE session_id = ? AND user_id = ? AND status = 'In Progress'
        ");
        $statsQuery->bind_param('ii', $sessionId, $userId);
        $statsQuery->execute();
        $stats = $statsQuery->get_result()->fetch_assoc();

        // Update session status
        $updateSession = $conn->prepare("
            UPDATE quiz_sessions
            SET status = 'Completed', 
                end_time = CURRENT_TIMESTAMP
            WHERE session_id = ?
        ");
        $updateSession->bind_param('i', $sessionId);
        $updateSession->execute();

        // Update user category stats
        $updateStats = $conn->prepare("
            UPDATE user_category_stats
            SET games_played = games_played + 1,
                total_score = total_score + ?,
                highest_score = GREATEST(highest_score, ?),
                average_score = ((average_score * games_played) + ?) / (games_played + 1),
                accuracy_percentage = ((accuracy_percentage * games_played) + ?) / (games_played + 1),
                last_played = CURRENT_TIMESTAMP
            WHERE user_id = ? AND category_id = ?
        ");
        $updateStats->bind_param('iiddii', 
            $stats['total_score'], 
            $stats['total_score'], 
            $stats['total_score'],
            $stats['accuracy_percentage'],
            $userId,
            $categoryId
        );
        $updateStats->execute();

        // Update leaderboard
        $updateLeaderboard = $conn->prepare("
            INSERT INTO leaderboards (category_id, user_id, score)
            VALUES (?, ?, ?)
        ");
        $updateLeaderboard->bind_param('iii', $categoryId, $userId, $stats['total_score']);
        $updateLeaderboard->execute();

        $conn->commit();

        echo json_encode([
            'success' => true,
            'stats' => $stats,
            'message' => 'Quiz completed successfully'
        ]);
    } catch (Exception $e) {
        $conn->rollback();
        die(json_encode([
            'success' => false,
            'message' => 'Failed to end quiz: ' . $e->getMessage()
        ]));
    }
}

$conn->close();
?>