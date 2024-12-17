<?php
// Quiz Page: Retro Gaming
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="quiz-retro-ajax.js"></script>
    <title>Retro Gaming Quiz</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: url('retro.jpg') center/cover no-repeat;
            color: white;
            padding: 20px;
        }

        .quiz-container {
            background: rgba(13, 17, 23, 0.95);
            padding: 2rem;
            border-radius: 24px;
            width: 100%;
            max-width: 600px;
            box-shadow: 0 8px 32px rgba(0, 255, 0, 0.2);
            border: 1px solid rgba(0, 255, 0, 0.1);
            backdrop-filter: blur(10px);
            text-align: center;
        }

        .quiz-header {
            margin-bottom: 2rem;
        }

        .quiz-header h1 {
            font-size: 2.5rem;
            text-shadow: 0 0 15px rgba(0, 255, 0, 0.5);
        }

        .quiz-question {
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .quiz-options {
            display: grid;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .quiz-option {
            padding: 1rem;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(0, 255, 0, 0.2);
            border-radius: 12px;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.2s ease;
        }

        .quiz-option:hover {
            background: rgba(0, 255, 0, 0.2);
            transform: translateY(-3px);
        }

        .quiz-timer {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            color: #00FF00;
        }

        .next-button {
            padding: 0.8rem 1.5rem;
            background: #00FF00;
            color: black;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: bold;
            text-transform: uppercase;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .next-button:hover {
            background: #00CC00;
            box-shadow: 0 0 15px rgba(0, 255, 0, 0.3);
        }

        @media (max-width: 480px) {
            .quiz-container {
                padding: 1.5rem;
            }
            
            .quiz-header h1 {
                font-size: 2rem;
            }
        }
        
        /* Question number and stats styling */
        .quiz-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding: 1rem;
            background: rgba(0, 255, 0, 0.1);
            border-radius: 12px;
            border: 1px solid rgba(0, 255, 0, 0.2);
        }

        #question-number {
            color: #00ff00;
            font-weight: bold;
            font-size: 1.1rem;
        }

        #timer {
            background: rgba(0, 255, 0, 0.2);
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: bold;
            color: #00ff00;
        }

        #points-available {
            color: #00ff00;
            font-weight: bold;
        }

        /* Question styling */
        #question-text {
            font-size: 1.4rem;
            margin-bottom: 2rem;
            padding: 1.5rem;
            background: rgba(0, 255, 0, 0.05);
            border-radius: 12px;
            border: 1px solid rgba(0, 255, 0, 0.2);
            text-align: left;
        }

        /* Answer buttons styling */
        #answers-container {
            display: grid;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        #answers-container button {
            padding: 1.2rem;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(0, 255, 0, 0.2);
            border-radius: 12px;
            color: white;
            font-size: 1.1rem;
            text-align: left;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        #answers-container button:hover {
            background: rgba(0, 255, 0, 0.1);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 255, 0, 0.2);
        }

        #answers-container button:before {
            content: "";
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 3px;
            background: #00ff00;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        #answers-container button:hover:before {
            opacity: 1;
        }

        /* Feedback message styling */
        #feedback-message {
            margin: 1rem 0;
            padding: 1rem;
            border-radius: 8px;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        #feedback-message.correct-answer {
            background: rgba(0, 255, 0, 0.2);
            color: #00ff00;
            border: 1px solid rgba(0, 255, 0, 0.3);
        }

        #feedback-message.wrong-answer {
            background: rgba(255, 0, 0, 0.2);
            color: #ff4444;
            border: 1px solid rgba(255, 0, 0, 0.3);
        }

        /* Results styling */
        #quiz-results {
            text-align: center;
        }

        #quiz-results h2 {
            color: #00ff00;
            font-size: 2rem;
            margin-bottom: 1.5rem;
        }

        .results-summary {
            background: rgba(0, 255, 0, 0.1);
            padding: 2rem;
            border-radius: 12px;
            border: 1px solid rgba(0, 255, 0, 0.2);
            margin-bottom: 2rem;
        }

        .results-summary p {
            font-size: 1.2rem;
            margin: 1rem 0;
        }

        .results-summary span {
            color: #00ff00;
            font-weight: bold;
        }

        /* Error message styling */
        #error-message {
            background: rgba(255, 0, 0, 0.2);
            color: #ff4444;
            padding: 1rem;
            border-radius: 8px;
            margin-top: 1rem;
            text-align: center;
        }

        /* Button styling */
        .primary-button {
            padding: 1rem 2rem;
            background: #00ff00;
            color: black;
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: bold;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .primary-button:hover {
            background: #00cc00;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 255, 0, 0.3);
        }
    </style>
</head>
<body>
    <div class="quiz-container" id="quiz-container">
        <!-- Quiz Intro -->
        <div id="quiz-intro">
            <h1>Retro Gaming Quiz</h1>
            <p>Test your knowledge of classic games from the 80s and 90s!</p>
            <button id="start-quiz" class="primary-button">Start Quiz</button>
        </div>

        <!-- Quiz Content -->
        <div id="quiz-content" style="display: none;">
            <div class="quiz-header">
                <span id="question-number"></span>
                <span id="timer"></span>
                <span id="points-available"></span>
            </div>
            <div id="question-text"></div>
            <div id="answers-container"></div>
            <div id="feedback-message"></div>
        </div>

        <!-- Quiz Results -->
        <div id="quiz-results" style="display: none;">
            <h2>Quiz Complete!</h2>
            <div class="results-summary">
                <p>Final Score: <span id="final-score"></span></p>
                <p>Accuracy: <span id="accuracy"></span></p>
                <p>Correct Answers: <span id="correct-answers"></span>/<span id="total-questions"></span></p>
            </div>
            <button id="restart-quiz" class="primary-button">Play Again</button>
        </div>

        <div id="error-message" style="display: none;"></div>
    </div>

    <script>
        <?php echo "
        let timerElement = document.getElementById('timer');
        let nextButton = document.getElementById('next-button');
        let options = document.querySelectorAll('.quiz-option');
        let timer = 15;
        let selectedOption = null;

        const startTimer = () => {
            const interval = setInterval(() => {
                if (timer > 0) {
                    timer--;
                    timerElement.textContent = timer;
                } else {
                    clearInterval(interval);
                    alert('Time is up! Moving to the next question.');
                }
            }, 1000);
        };

        const handleOptionClick = (event) => {
            if (selectedOption) {
                selectedOption.style.background = 'rgba(255, 255, 255, 0.05)';
            }
            selectedOption = event.target;
            selectedOption.style.background = 'rgba(0, 255, 0, 0.5)';
        };

        options.forEach(option => {
            option.addEventListener('click', handleOptionClick);
        });

        nextButton.addEventListener('click', () => {
            if (selectedOption) {
                alert(`You selected: ${selectedOption.dataset.value}`);
            } else {
                alert('Please select an option before proceeding.');
            }
        });

        startTimer();
        "; ?>
    </script>
</body>
</html>
