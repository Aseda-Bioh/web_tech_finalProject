<?php
header('Content-Type: application/json');

// Database connection configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'kwabena.bioh');
define('DB_PASS', 'FatherAbraham2');
define('DB_NAME', 'webtech_fall2024_kwabena_bioh');

// Response array
$response = [
    'success' => false,
    'message' => '',
    'redirect' => ''
];

try {
    // Create database connection
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    // Check connection
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Check if request method is POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get and sanitize input
        $firstName = filter_var(trim($_POST['firstname'] ?? ''), FILTER_SANITIZE_STRING);
        $lastName = filter_var(trim($_POST['lastname'] ?? ''), FILTER_SANITIZE_STRING);
        $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'] ?? '';

        // Validate inputs
        if (empty($firstName)) {
            throw new Exception('First name is required');
        }

        if (empty($lastName)) {
            throw new Exception('Last name is required');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Invalid email format');
        }

        if (strlen($password) < 8) {
            throw new Exception('Password must be at least 8 characters long');
        }

        // Check if email already exists
        $checkStmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
        $checkStmt->bind_param("s", $email);
        $checkStmt->execute();
        $result = $checkStmt->get_result();

        if ($result->num_rows > 0) {
            throw new Exception('Email already registered');
        }
        $checkStmt->close();

        // Hash password
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Begin transaction
        $conn->begin_transaction();

        try {
            // Insert new user
            $insertStmt = $conn->prepare("
                INSERT INTO users (first_name, last_name, email, password_hash)
                VALUES (?, ?, ?, ?)
            ");
            $insertStmt->bind_param("ssss", $firstName, $lastName, $email, $passwordHash);
            $insertStmt->execute();
            $userId = $conn->insert_id;
            $insertStmt->close();

            // Initialize user_category_stats
            $categoryStmt = $conn->prepare("
                INSERT INTO user_category_stats (user_id, category_id)
                SELECT ?, category_id FROM categories WHERE is_active = 1
            ");
            $categoryStmt->bind_param("i", $userId);
            $categoryStmt->execute();
            $categoryStmt->close();

            // Initialize user achievements
            $achievementStmt = $conn->prepare("
                INSERT INTO user_achievements (user_id, achievement_id)
                SELECT ?, achievement_id FROM achievements WHERE is_active = 1
            ");
            $achievementStmt->bind_param("i", $userId);
            $achievementStmt->execute();
            $achievementStmt->close();

            // Log first-time activity
            $activityStmt = $conn->prepare("
                INSERT INTO activity_log (user_id, activity_type, description)
                VALUES (?, 'First_Time', 'User registered successfully')
            ");
            $activityStmt->bind_param("i", $userId);
            $activityStmt->execute();
            $activityStmt->close();

            // Commit transaction
            $conn->commit();

            $response['success'] = true;
            $response['message'] = 'Account created successfully! Please login to continue.';
            $response['redirect'] = 'login.php';

        } catch (Exception $e) {
            // Rollback transaction if any error occurs
            $conn->rollback();
            throw $e;
        }
    } else {
        throw new Exception('Invalid request method');
    }

} catch (Exception $e) {
    $response['success'] = false;
    $response['message'] = $e->getMessage();
} finally {
    // Close database connection if it exists
    if (isset($conn)) {
        $conn->close();
    }
}

// Send JSON response
echo json_encode($response);