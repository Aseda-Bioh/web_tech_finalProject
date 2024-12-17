let quizState = {
    sessionId: null,
    currentQuestion: null,
    startTime: null,
    timer: null,
    score: 0,
    questionNumber: 0,
    totalQuestions: 5,
    timeLimit: 60,
    isAnswered: false
};

// Initialize when document is ready
$(document).ready(function() {
    // Show intro screen and hide other sections
    $('#quiz-content, #quiz-results').hide();
    $('#quiz-intro').show();

    // Start quiz button handler
    $('#start-quiz').click(function() {
        initializeQuiz();
    });

    // Restart quiz button handler
    $('#restart-quiz').click(function() {
        location.reload();
    });
});

// Initialize quiz
function initializeQuiz() {
    $.ajax({
        url: 'quiz_rpg_logic.php',
        type: 'POST',
        data: {
            action: 'start_quiz'
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                quizState.sessionId = response.session_id;
                $('#quiz-intro').hide();
                $('#quiz-content').show();
                loadNextQuestion();
            } else {
                showError(response.message);
                if (response.redirect) {
                    window.location.href = response.redirect;
                }
            }
        },
        error: function() {
            showError('Failed to start quiz. Please try again.');
        }
    });
}

// Load next question
function loadNextQuestion() {
    $.ajax({
        url: 'quiz_rpg_logic.php',
        type: 'POST',
        data: {
            action: 'get_question',
            session_id: quizState.sessionId
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                if (response.question) {
                    displayQuestion(response.question);
                    startTimer();
                    quizState.questionNumber++;
                    quizState.isAnswered = false;
                } else {
                    endQuiz();
                }
            } else {
                if (response.quiz_complete) {
                    endQuiz();
                } else {
                    showError(response.message);
                }
            }
        },
        error: function() {
            showError('Failed to load question. Please try again.');
        }
    });
}

// Display question and answers
function displayQuestion(question) {
    quizState.currentQuestion = question;
    quizState.startTime = new Date();
    
    // Update question number and points
    $('#question-number').text(`Question ${quizState.questionNumber}/${quizState.totalQuestions}`);
    $('#points-available').text(`Points: ${question.points}`);
    
    // Display question
    $('#question-text').text(question.text);
    
    // Clear previous answers
    $('#answers-container').empty();
    
    // Add new answer buttons
    question.answers.forEach(answer => {
        const button = $('<button></button>')
            .addClass('answer-button')
            .text(answer.answer_text)
            .data('id', answer.answer_id)
            .click(() => submitAnswer(answer.answer_id));
        
        $('#answers-container').append(button);
    });

    // Reset timer display
    $('#timer').text(question.time_limit);
}

// Submit answer
function submitAnswer(answerId) {
    if (quizState.isAnswered) return;
    quizState.isAnswered = true;

    const timeTaken = Math.round((new Date() - quizState.startTime) / 1000);
    clearInterval(quizState.timer);

    $.ajax({
        url: 'quiz_rpg_logic.php',
        type: 'POST',
        data: {
            action: 'submit_answer',
            session_id: quizState.sessionId,
            question_id: quizState.currentQuestion.id,
            answer_id: answerId,
            time_taken: timeTaken
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                // Update score
                quizState.score += response.points_earned;
                
                // Show feedback
                showAnswerFeedback(response.correct, response.points_earned);
                
                // Disable all answer buttons
                $('.answer-button').prop('disabled', true);
                
                // Move to next question after delay
                setTimeout(() => {
                    if (quizState.questionNumber < quizState.totalQuestions) {
                        loadNextQuestion();
                    } else {
                        endQuiz();
                    }
                }, 2000);
            } else {
                showError(response.message);
            }
        },
        error: function() {
            showError('Failed to submit answer. Please try again.');
        }
    });
}

// Timer management
function startTimer() {
    let timeLeft = quizState.currentQuestion.time_limit;
    updateTimerDisplay(timeLeft);

    clearInterval(quizState.timer);
    quizState.timer = setInterval(() => {
        timeLeft--;
        updateTimerDisplay(timeLeft);

        if (timeLeft <= 0) {
            clearInterval(quizState.timer);
            if (!quizState.isAnswered) {
                handleTimeUp();
            }
        }
    }, 1000);
}

// End quiz
function endQuiz() {
    $.ajax({
        url: 'quiz_rpg_logic.php',
        type: 'POST',
        data: {
            action: 'end_quiz',
            session_id: quizState.sessionId
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                displayResults(response.stats);
                $('#quiz-content').hide();
                $('#quiz-results').show();
            } else {
                showError(response.message);
            }
        },
        error: function() {
            showError('Failed to end quiz. Please try again.');
        }
    });
}

// UI Helper Functions
function updateTimerDisplay(timeLeft) {
    const minutes = Math.floor(timeLeft / 60);
    const seconds = timeLeft % 60;
    $('#timer').text(`${minutes}:${seconds.toString().padStart(2, '0')}`);
}

function showAnswerFeedback(correct, points) {
    const feedback = correct ? 
        `Correct! +${points} points` : 
        'Incorrect!';
    
    $('#feedback-message')
        .removeClass('correct-answer wrong-answer')
        .addClass(correct ? 'correct-answer' : 'wrong-answer')
        .text(feedback)
        .fadeIn()
        .delay(1500)
        .fadeOut();
}

function displayResults(stats) {
    $('#final-score').text(stats.total_score);
    $('#accuracy').text(`${Math.round(stats.accuracy_percentage)}%`);
    $('#correct-answers').text(stats.correct_answers);
    $('#total-questions').text(stats.questions_answered);
}

function handleTimeUp() {
    showAnswerFeedback(false, 0);
    $('.answer-button').prop('disabled', true);
    
    setTimeout(() => {
        if (quizState.questionNumber < quizState.totalQuestions) {
            loadNextQuestion();
        } else {
            endQuiz();
        }
    }, 2000);
}

function showError(message) {
    $('#error-message')
        .text(message)
        .fadeIn()
        .delay(3000)
        .fadeOut();
}