<?php
// Dashboard Page: Gaming Trivia Quest
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Gaming Trivia Quest</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #0a0a0a;
            color: white;
            min-height: 100vh;
        }

        nav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            padding: 1.5rem 2rem;
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(10px);
            z-index: 100;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: #00ff00;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            font-size: 1rem;
            transition: color 0.3s ease;
        }

        .nav-links a:hover {
            color: #00ff00;
        }

        .dashboard-container {
            padding-top: 80px;
            max-width: 1200px;
            margin: 0 auto;
            padding: 100px 20px 20px;
        }

        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            background: rgba(0, 255, 0, 0.1);
            padding: 2rem;
            border-radius: 15px;
            border: 1px solid rgba(0, 255, 0, 0.2);
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .user-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: #00ff00;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            border: 3px solid #00ff00;
        }

        .user-stats {
            display: flex;
            gap: 2rem;
        }

        .stat-item {
            text-align: center;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: bold;
            color: #00ff00;
        }

        .stat-label {
            font-size: 0.9rem;
            color: #888;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .dashboard-card {
            background: rgba(0, 0, 0, 0.7);
            border-radius: 15px;
            padding: 1.5rem;
            border: 1px solid rgba(0, 255, 0, 0.2);
            transition: all 0.3s ease;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
            border-color: #00ff00;
            box-shadow: 0 5px 15px rgba(0, 255, 0, 0.2);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .card-title {
            font-size: 1.2rem;
            color: #00ff00;
            font-weight: bold;
        }

        .achievement-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(60px, 1fr));
            gap: 1rem;
        }

        .achievement-item {
            width: 60px;
            height: 60px;
            background: rgba(0, 255, 0, 0.1);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: #00ff00;
            border: 1px solid rgba(0, 255, 0, 0.3);
            transition: all 0.3s ease;
        }

        .achievement-item.locked {
            opacity: 0.5;
            filter: grayscale(1);
        }

        .achievement-item:hover {
            transform: scale(1.1);
            border-color: #00ff00;
        }

        .recent-activity {
            margin-top: 1rem;
        }

        .activity-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.8rem 0;
            border-bottom: 1px solid rgba(0, 255, 0, 0.1);
        }

        .activity-icon {
            width: 40px;
            height: 40px;
            background: rgba(0, 255, 0, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #00ff00;
        }

        .progress-bar {
            height: 10px;
            background: rgba(0, 255, 0, 0.1);
            border-radius: 5px;
            overflow: hidden;
            margin-top: 1rem;
        }

        .progress-fill {
            height: 100%;
            background: #00ff00;
            transition: width 0.3s ease;
        }

        .quick-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .quick-stat {
            background: rgba(0, 255, 0, 0.1);
            padding: 1rem;
            border-radius: 10px;
            text-align: center;
        }

        .notification-dot {
            width: 8px;
            height: 8px;
            background: #00ff00;
            border-radius: 50%;
            position: absolute;
            top: -4px;
            right: -4px;
        }

        @media (max-width: 768px) {
            .dashboard-header {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }

            .user-stats {
                flex-wrap: wrap;
                justify-content: center;
            }

            .nav-links {
                display: none;
            }
        }
    </style>
</head>
<body>
    <nav>
        <div class="logo">TriviaQuest</div>
        <div class="nav-links">
            <a href="home.php">Home</a>
            <a href="#">Leaderboard</a>
            <a href="about.php">About</a>
        </div>
    </nav>

    <div class="dashboard-container">
        <div class="dashboard-header">
            <div class="user-info">
                <div class="user-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <div>
                    <h2>Welcome back, Player1</h2>
                    <p>Level 42 Quiz Master</p>
                </div>
            </div>
            <div class="user-stats">
                <div class="stat-item">
                    <div class="stat-value">1,337</div>
                    <div class="stat-label">Total Points</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">87%</div>
                    <div class="stat-label">Accuracy</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">42</div>
                    <div class="stat-label">Quizzes Complete</div>
                </div>
            </div>
        </div>

        <div class="dashboard-grid">
            <div class="dashboard-card">
                <div class="card-header">
                    <h3 class="card-title">Recent Achievements</h3>
                    <i class="fas fa-trophy" style="color: #00ff00;"></i>
                </div>
                <div class="achievement-grid">
                    <div class="achievement-item"><i class="fas fa-star"></i></div>
                    <div class="achievement-item"><i class="fas fa-crown"></i></div>
                    <div class="achievement-item"><i class="fas fa-medal"></i></div>
                    <div class="achievement-item locked"><i class="fas fa-lock"></i></div>
                    <div class="achievement-item locked"><i class="fas fa-lock"></i></div>
                    <div class="achievement-item locked"><i class="fas fa-lock"></i></div>
                </div>
            </div>

            <div class="dashboard-card">
                <div class="card-header">
                    <h3 class="card-title">Current Progress</h3>
                    <i class="fas fa-chart-line" style="color: #00ff00;"></i>
                </div>
                <div class="quick-stats">
                    <div class="quick-stat">
                        <div class="stat-value">8/10</div>
                        <div class="stat-label">Daily Goals</div>
                    </div>
                    <div class="quick-stat">
                        <div class="stat-value">Level 5</div>
                        <div class="stat-label">RPG Expert</div>
                    </div>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: 75%;"></div>
                </div>
            </div>

            <div class="dashboard-card">
                <div class="card-header">
                    <h3 class="card-title">Recent Activity</h3>
                    <i class="fas fa-history" style="color: #00ff00;"></i>
                </div>
                <div class="recent-activity">
                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-gamepad"></i>
                        </div>
                        <div>
                            <div>Completed Retro Gaming Quiz</div>
                            <small style="color: #888;">2 hours ago</small>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-trophy"></i>
                        </div>
                        <div>
                            <div>Earned "RPG Master" Badge</div>
                            <small style="color: #888;">Yesterday</small>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-star"></i>
                        </div>
                        <div>
                            <div>New High Score in FPS Category</div>
                            <small style="color: #888;">2 days ago</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Add hover effects to dashboard cards
            const cards = document.querySelectorAll('.dashboard-card');
            cards.forEach(card => {
                card.addEventListener('mouseenter', () => {
                    card.style.transform = 'translateY(-10px)';
                });
                card.addEventListener('mouseleave', () => {
                    card.style.transform = 'translateY(0)';
                });
            });

            // Add hover effects to achievement items
            const achievements = document.querySelectorAll('.achievement-item:not(.locked)');
            achievements.forEach(achievement => {
                achievement.addEventListener('mouseenter', () => {
                    achievement.style.transform = 'scale(1.1)';
                });
                achievement.addEventListener('mouseleave', () => {
                    achievement.style.transform = 'scale(1)';
                });
            });

            // Simulate progress bar animation
            const progressFill = document.querySelector('.progress-fill');
            setTimeout(() => {
                progressFill.style.width = '75%';
            }, 300);
        });
    </script>
</body>
</html>