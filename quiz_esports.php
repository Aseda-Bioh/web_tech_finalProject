<?php
// Quiz Page: Esports Category
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Esports Quiz</title>
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #000; /* Dark Background */
            color: #00ff00; /* Neon Green Text */
            margin: 0;
            padding: 0;
        }

        h1 {
            color: #00ff00; /* Neon Green Title */
            text-align: center;
            text-shadow: 0 0 10px #00ff00;
            margin: 20px 0;
        }

        /* Quiz Container */
        .quiz-container {
            background-color: #001a00; /* Dark Green Background */
            border-radius: 10px;
            padding: 20px;
            margin: 20px auto;
            max-width: 700px;
            box-shadow: 0 0 15px #00ff00;
        }

        /* Question Header */
        .question-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            font-size: 18px;
            font-weight: bold;
        }

        .question-header span {
            color: #00ff00;
            text-shadow: 0 0 5px #00ff00;
        }

        /* Question Text */
        .question-text {
            font-size: 22px;
            text-align: center;
            padding: 15px;
            border: 2px solid #00ff00;
            border-radius: 8px;
            margin-bottom: 20px;
            background-color: #002200;
            color: #e0e0e0; /* Light Text for contrast */
        }

        /* Answer Options */
        .answer-option {
            background-color: #001a00;
            color: #e0e0e0;
            border: 2px solid #00ff00;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
            font-size: 18px;
            cursor: pointer;
            text-align: center;
            box-shadow: 0 0 5px #00ff00;
            transition: background-color 0.3s, box-shadow 0.3s;
        }

        .answer-option:hover {
            background-color: #003300;
            box-shadow: 0 0 10px #00ff00;
        }

        .answer-option.selected {
            background-color: #003300;
            box-shadow: 0 0 10px #00ff00;
        }

        button {
            display: block;
            background-color: #00ff00;
            color: #000;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            margin: 20px auto 0;
            font-size: 18px;
            cursor: pointer;
            box-shadow: 0 0 5px #00ff00;
        }

        button:hover {
            background-color: #00cc00;
            box-shadow: 0 0 10px #00cc00;
        }
    </style>
</head>
<body>
    <h1>Esports Quiz</h1>
    <div class="quiz-container">
        <!-- Question Header -->
        <div class="question-header">
            <span>Question 1/5</span>
            <span>⏱ 0:25</span>
            <span>Points: 100</span>
        </div>

        <!-- Question Text -->
        <div class="question-text">
            Which company developed Counter-Strike: Global Offensive?
        </div>

        <!-- Answer Options -->
        <div class="answer-option" onclick="selectOption(this)">Valve</div>
        <div class="answer-option" onclick="selectOption(this)">EA</div>
        <div class="answer-option" onclick="selectOption(this)">Activision</div>
        <div class="answer-option" onclick="selectOption(this)">Ubisoft</div>

        <!-- Submit Button -->
        <button>Submit</button>
    </div>

    <script>
        // JavaScript to handle answer selection
        function selectOption(option) {
            const allOptions = document.querySelectorAll('.answer-option');
            allOptions.forEach(opt => opt.classList.remove('selected'));
            option.classList.add('selected');
        }
    </script>
</body>
</html>
