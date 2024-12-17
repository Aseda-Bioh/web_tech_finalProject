<?php
// Leaderboard Page: Gaming Trivia Quest
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TriviaQuest - Leaderboard</title>
    <style>
        <?php echo "
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        body {
            background-color: #0a0a0a;
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .leaderboard-container {
            width: 100%;
            max-width: 800px;
            background: rgba(37, 43, 72, 0.95);
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 8px 32px rgba(0, 255, 0, 0.2);
            border: 1px solid rgba(0, 255, 0, 0.1);
        }

        .leaderboard-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .leaderboard-header h1 {
            font-size: 2rem;
            color: #00FF00;
            text-shadow: 0 0 10px rgba(0, 255, 0, 0.5);
        }

        .leaderboard-header p {
            color: #7a8194;
            font-size: 0.9rem;
        }

        .leaderboard-list {
            list-style: none;
        }

        .leaderboard-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(255, 255, 255, 0.05);
            padding: 12px 16px;
            margin-bottom: 10px;
            border-radius: 12px;
            border: 1px solid rgba(0, 255, 0, 0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .leaderboard-item:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 16px rgba(0, 255, 0, 0.3);
        }

        .rank {
            font-size: 1.2rem;
            font-weight: bold;
            color: #00FF00;
        }

        .player-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .player-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #fff;
            overflow: hidden;
        }

        .player-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .player-name {
            font-size: 1rem;
            font-weight: bold;
            color: white;
        }

        .player-score {
            font-size: 1rem;
            font-weight: bold;
            color: #00FF00;
        }

        @media (max-width: 480px) {
            .leaderboard-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }

            .player-info {
                gap: 8px;
            }
        }
        "; ?>
    </style>
</head>
<body>
    <div class="leaderboard-container">
        <div class="leaderboard-header">
            <h1>Leaderboard</h1>
            <p>Compete and climb to the top! See where you stand among other players.</p>
        </div>

        <ul class="leaderboard-list" id="leaderboard-list">
            <!-- Leaderboard items will be dynamically added here -->
        </ul>
    </div>

    <script>
        <?php echo "
        document.addEventListener('DOMContentLoaded', () => {
            const leaderboardData = [
                { rank: 1, name: 'PlayerOne', score: 5000, avatar: 'https://via.placeholder.com/40' },
                { rank: 2, name: 'GameMaster', score: 4200, avatar: 'https://via.placeholder.com/40' },
                { rank: 3, name: 'QuestHunter', score: 3800, avatar: 'https://via.placeholder.com/40' },
                { rank: 4, name: 'RPGPro', score: 3500, avatar: 'https://via.placeholder.com/40' },
                { rank: 5, name: 'SpeedRunner', score: 3200, avatar: 'https://via.placeholder.com/40' },
            ];

            const leaderboardList = document.getElementById('leaderboard-list');

            leaderboardData.forEach(player => {
                const item = document.createElement('li');
                item.classList.add('leaderboard-item');

                item.innerHTML = `
                    <span class='rank'>#${player.rank}</span>
                    <div class='player-info'>
                        <div class='player-avatar'>
                            <img src='${player.avatar}' alt='${player.name}'>
                        </div>
                        <span class='player-name'>${player.name}</span>
                    </div>
                    <span class='player-score'>${player.score} pts</span>
                `;

                leaderboardList.appendChild(item);
            });
        });
        "; ?>
    </script>

</body>
</html>
