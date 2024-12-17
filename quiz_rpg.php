<?php
// Quiz Page: Open-World RPGs
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="quiz-rpg-ajax.js"></script>
    <title>Open-World RPG Quiz</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: url('open_rpg.jpg') no-repeat center center fixed;
            background-size: cover;
            color: white;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .quiz-container {
            background: rgba(13, 17, 23, 0.95);
            border: 1px solid rgba(0, 255, 0, 0.1);
            box-shadow: 0 8px 32px rgba(0, 255, 0, 0.2);
            border-radius: 16px;
            padding: 2rem;
            width: 90%;
            max-width: 600px;
            text-align: center;
        }

        .quiz-header {
            margin-bottom: 1.5rem;
        }

        .quiz-header h1 {
            font-size: 2.5rem;
            color: #00FF00;
            text-shadow: 0 0 10px rgba(0, 255, 0, 0.5);
        }

        .quiz-timer {
            font-size: 1.2rem;
            margin-bottom: 1rem;
            color: #b0b0b0;
        }

        .quiz-question {
            margin-bottom: 1.5rem;
            font-size: 1.4rem;
        }

        .quiz-options {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .quiz-option {
            padding: 1rem;
            background: rgba(0, 255, 0, 0.05);
            border: 1px solid rgba(0, 255, 0, 0.2);
            border-radius: 8px;
            color: white;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.3s, transform 0.3s;
        }

        .quiz-option:hover {
            background: rgba(0, 255, 0, 0.1);
            transform: scale(1.05);
        }

        .quiz-option.correct {
            background: #00FF00;
            color: black;
        }

        .quiz-option.wrong {
            background: #FF4444;
            color: white;
        }

        .quiz-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .quiz-button {
            padding: 0.8rem 1.5rem;
            background: #00FF00;
            border: none;
            border-radius: 8px;
            color: black;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
        }

        .quiz-button:hover {
            background: #00CC00;
        }

        .quiz-score {
            font-size: 1.2rem;
            color: #00FF00;
        }

        @media (max-width: 480px) {
            .quiz-container {
                padding: 1.5rem;
            }

            .quiz-header h1 {
                font-size: 2rem;
            }

            .quiz-question {
                font-size: 1.2rem;
            }

            .quiz-options {
                grid-template-columns: 1fr;
            }
        }

        #question-text {
            font-size: 1.6rem;
            margin: 1.5rem 0;
            padding: 1.5rem;
            background: rgba(0, 255, 0, 0.05);
            border: 1px solid rgba(0, 255, 0, 0.2);
            border-radius: 12px;
            text-align: left;
            color: white;
        }

        #answers-container {
            display: grid;
            gap: 1rem;
            margin: 1.5rem 0;
        }

        #answers-container button {
            padding: 1.2rem;
            background: rgba(0, 255, 0, 0.05);
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
            border-color: #00ff00;
        }

        #answers-container button:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        /* Question Header Styling */
        #question-number {
            font-size: 1.2rem;
            color: #00ff00;
            font-weight: bold;
        }

        #timer {
            font-size: 1.2rem;
            color: #00ff00;
            padding: 0.5rem 1rem;
            background: rgba(0, 255, 0, 0.1);
            border-radius: 20px;
            border: 1px solid rgba(0, 255, 0, 0.2);
        }

        #points-available {
            font-size: 1.2rem;
            color: #00ff00;
        }

        /* Feedback Message Styling */
        #feedback-message {
            margin: 1rem 0;
            padding: 1rem;
            border-radius: 8px;
            font-size: 1.2rem;
            font-weight: bold;
            text-align: center;
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

        /* Quiz Header Layout */
        .quiz-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding: 1rem;
            background: rgba(0, 255, 0, 0.05);
            border-radius: 12px;
            border: 1px solid rgba(0, 255, 0, 0.2);
        }

        #quiz-results h2 {
            color: #00ff00;
            font-size: 2rem;
            margin-bottom: 1.5rem;
            text-shadow: 0 0 10px rgba(0, 255, 0, 0.3);
        }

        .results-summary {
            background: rgba(0, 255, 0, 0.05);
            padding: 2rem;
            border-radius: 12px;
            border: 1px solid rgba(0, 255, 0, 0.2);
            margin: 1.5rem 0;
        }

        .results-summary p {
            font-size: 1.2rem;
            margin: 1rem 0;
            color: white;
        }

        .results-summary span {
            color: #00ff00;
            font-weight: bold;
        }

        /* Media Queries */
        @media (max-width: 480px) {
            #question-text {
                font-size: 1.3rem;
                padding: 1rem;
            }

            #answers-container button {
                padding: 1rem;
                font-size: 1rem;
            }

            .quiz-header {
                flex-direction: column;
                gap: 0.5rem;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="quiz-container">
        <!-- Quiz Intro -->
        <div id="quiz-intro">
            <h1>Open-World RPG Quiz</h1>
            <p>Test your knowledge of vast worlds and epic adventures!</p>
            <button id="start-quiz" class="quiz-button">Start Quiz</button>
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
            <button id="restart-quiz" class="quiz-button">Play Again</button>
        </div>

        <div id="error-message" style="display: none;"></div>
    </div>
</body>
</html>
