let quizState = {
    sessionId: null,
    currentQuestion: null,
    startTime: null,
    timer: null,
    score: 0,
    questionNumber: 0,
    totalQuestions: 10
};

// Initialize quiz
function initializeQuiz() {
    $.ajax({
        url: 'quiz_retro_logic.php',
        type: 'POST',
        data: {
            action: 'start_quiz'
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                quizState.sessionId = response.session_id;
                loadNextQuestion();
                updateUI('quiz-start');
            } else {
                if (response.redirect) {
                    window.location.href = response.redirect;
                } else {
                    showError(response.message);
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
        url: 'quiz_retro_logic.php',
        type: 'POST',
        data: {
            action: 'get_question',
            session_id: quizState.sessionId
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                if (response.question) {
                    quizState.currentQuestion = response.question;
                    quizState.questionNumber++;
                    quizState.startTime = new Date();
                    displayQuestion(response.question);
                    startTimer(response.question.time_limit);
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

// Submit answer
function submitAnswer(answerId) {
    clearInterval(quizState.timer);
    const timeTaken = Math.round((new Date() - quizState.startTime) / 1000);

    $.ajax({
        url: 'quiz_retro_logic.php',
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
                quizState.score += response.points_earned;
                showAnswerFeedback(response.correct, response.points_earned);
                
                if (quizState.questionNumber < quizState.totalQuestions) {
                    setTimeout(loadNextQuestion, 2000);
                } else {
                    endQuiz();
                }
            } else {
                showError(response.message);
            }
        },
        error: function() {
            showError('Failed to submit answer. Please try again.');
        }
    });
}

// End quiz
function endQuiz() {
    $.ajax({
        url: 'quiz_retro_logic.php',
        type: 'POST',
        data: {
            action: 'end_quiz',
            session_id: quizState.sessionId
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                displayResults(response.stats);
                updateUI('quiz-end');
            } else {
                showError(response.message);
            }
        },
        error: function() {
            showError('Failed to end quiz. Please try again.');
        }
    });
}

// Timer management
function startTimer(timeLimit) {
    let timeLeft = timeLimit;
    updateTimerDisplay(timeLeft);

    clearInterval(quizState.timer);
    quizState.timer = setInterval(() => {
        timeLeft--;
        updateTimerDisplay(timeLeft);

        if (timeLeft <= 0) {
            clearInterval(quizState.timer);
            handleTimeUp();
        }
    }, 1000);
}

// UI update functions
function displayQuestion(question) {
    $('#question-text').text(question.text);
    $('#question-number').text(`Question ${quizState.questionNumber} of ${quizState.totalQuestions}`);
    $('#points-available').text(`Points: ${question.points}`);

    const answersContainer = $('#answers-container');
    answersContainer.empty();

    question.answers.forEach(answer => {
        const answerBtn = $(`<button class="answer-button" data-id="${answer.answer_id}">
            ${answer.answer_text}
        </button>`);
        
        answerBtn.click(function() {
            $('.answer-button').prop('disabled', true);
            submitAnswer(answer.answer_id);
        });

        answersContainer.append(answerBtn);
    });
}

function showAnswerFeedback(correct, points) {
    const feedback = correct ? 
        `Correct! +${points} points` : 
        'Incorrect!';
    
    const feedbackClass = correct ? 'correct-answer' : 'wrong-answer';
    
    $('#feedback-message')
        .text(feedback)
        .removeClass('correct-answer wrong-answer')
        .addClass(feedbackClass)
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

function updateTimerDisplay(timeLeft) {
    const minutes = Math.floor(timeLeft / 60);
    const seconds = timeLeft % 60;
    $('#timer').text(`${minutes}:${seconds.toString().padStart(2, '0')}`);
}

function handleTimeUp() {
    $('.answer-button').prop('disabled', true);
    showAnswerFeedback(false, 0);
    setTimeout(loadNextQuestion, 2000);
}

function showError(message) {
    $('#error-message')
        .text(message)
        .fadeIn()
        .delay(3000)
        .fadeOut();
}

function updateUI(state) {
    switch(state) {
        case 'quiz-start':
            $('#quiz-intro').hide();
            $('#quiz-content').show();
            $('#quiz-results').hide();
            break;
        case 'quiz-end':
            $('#quiz-content').hide();
            $('#quiz-results').show();
            break;
    }
}

// Event handlers
$(document).ready(function() {
    $('#start-quiz').click(initializeQuiz);
    $('#restart-quiz').click(function() {
        window.location.reload();
    });
});