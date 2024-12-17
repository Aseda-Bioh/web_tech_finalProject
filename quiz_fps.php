<?php
// Quiz Page: First Person Shooter (FPS)
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="quiz-fps-ajax.js"></script>
    <title>FPS Quiz - Gaming Trivia Quest</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: url('fps.jpg') center/cover no-repeat fixed;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: white;
            padding: 20px;
        }

        .quiz-container {
            background: rgba(0, 0, 0, 0.8);
            border: 2px solid #00FF00;
            border-radius: 12px;
            padding: 2rem;
            max-width: 800px;
            width: 90%;
            box-shadow: 0 0 20px rgba(0, 255, 0, 0.5);
        }

        /* Quiz Header Styling */
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

        /* Question Styling */
        #question-text {
            font-size: 1.4rem;
            margin-bottom: 2rem;
            padding: 1.5rem;
            background: rgba(0, 255, 0, 0.05);
            border-radius: 12px;
            border: 1px solid rgba(0, 255, 0, 0.2);
            text-align: left;
            color: white;
        }

        /* Answer Buttons Styling */
        #answers-container {
            display: grid;
            gap: 1rem;
            margin-bottom: 2rem;
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

        /* Feedback Message Styling */
        #feedback-message {
            margin: 1rem 0;
            padding: 1rem;
            border-radius: 8px;
            font-weight: bold;
            transition: all 0.3s ease;
            text-align: center;
            font-size: 1.2rem;
            display: none;
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

        /* Quiz Results Styling */
        #quiz-results {
            text-align: center;
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
            margin-bottom: 2rem;
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

        /* Button Styling */
        .quiz-button {
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

        .quiz-button:hover {
            background: #00cc00;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 255, 0, 0.3);
        }

        /* Error Message Styling */
        #error-message {
            background: rgba(255, 0, 0, 0.2);
            color: #ff4444;
            padding: 1rem;
            border-radius: 8px;
            margin-top: 1rem;
            text-align: center;
            display: none;
        }

        @media (max-width: 768px) {
            .quiz-container {
                padding: 1.5rem;
            }

            #question-text {
                font-size: 1.2rem;
                padding: 1rem;
            }

            #answers-container button {
                padding: 1rem;
                font-size: 1rem;
            }

            .quiz-header {
                flex-direction: column;
                gap: 0.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="quiz-container">
        <!-- Quiz Intro -->
        <div id="quiz-intro">
            <h1 style="font-size: 2.5rem; color: #00FF00; text-shadow: 0 0 10px rgba(0, 255, 0, 0.7); margin-bottom: 1rem;">
                First Person Shooter Quiz
            </h1>
            <p style="font-size: 1.2rem; color: #aaa; margin-bottom: 2rem;">
                Test your FPS knowledge! Challenge yourself with questions about the most action-packed gaming genre.
            </p>
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