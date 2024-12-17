<?php
// Home Page: Gaming Trivia Quest
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gaming Trivia Quest</title>
    <style>
        <?php echo "
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

        .hero-section {
            position: relative;
            height: 100vh;
            overflow: hidden;
        }

        .hero-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('download.gif') center/cover;
            opacity: 0.6;
            z-index: 1;
        }

        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at center, rgba(0,255,0,0.1) 0%, rgba(0,0,0,0.8) 100%);
            z-index: 2;
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

        .hero-content {
            position: relative;
            z-index: 3;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 2rem;
        }

        .hero-title {
            font-size: 4rem;
            font-weight: 800;
            margin-bottom: 1rem;
            text-transform: uppercase;
            letter-spacing: 3px;
            color: #fff;
            text-shadow: 0 0 10px rgba(0,255,0,0.5);
        }

        .hero-subtitle {
            font-size: 1.5rem;
            margin-bottom: 2rem;
            color: #b0b0b0;
        }

        .cta-buttons {
            display: flex;
            gap: 1rem;
        }

        .cta-button {
            padding: 1rem 2rem;
            font-size: 1.1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }

        .primary-button {
            background: #00ff00;
            color: #000;
        }

        .primary-button:hover {
            background: #00cc00;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,255,0,0.3);
        }

        .secondary-button {
            background: transparent;
            color: #00ff00;
            border: 2px solid #00ff00;
        }

        .secondary-button:hover {
            background: rgba(0,255,0,0.1);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,255,0,0.2);
        }

        .featured-categories {
            position: absolute;
            bottom: 2rem;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 2rem;
            z-index: 3;
        }

        .category-card {
            background: rgba(0, 0, 0, 0.7);
            padding: 1rem;
            border-radius: 10px;
            border: 1px solid rgba(0,255,0,0.3);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .category-card:hover {
            transform: translateY(-5px);
            border-color: #00ff00;
            box-shadow: 0 5px 15px rgba(0,255,0,0.2);
        }

        .category-icon {
            font-size: 2rem;
            color: #00ff00;
            margin-bottom: 0.5rem;
        }

        .category-name {
            font-size: 1rem;
            color: white;
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }

            .hero-subtitle {
                font-size: 1.2rem;
            }

            .featured-categories {
                flex-direction: column;
                bottom: auto;
                top: 50%;
                transform: translate(-50%, -50%);
                right: 2rem;
                left: auto;
            }

            .nav-links {
                display: none;
            }
        }
        "; ?>
    </style>
</head>
<body>
    <div class="hero-section">
        <div class="hero-background"></div>
        <div class="hero-overlay"></div>
        
        <nav>
            <div class="logo">TriviaQuest</div>
            <div class="nav-links">
                <a href="#">Home</a>
                <a href="#">Leaderboard</a>
                <a href="dashboard.php">Profile</a>
            </div>
        </nav>

        <div class="hero-content">
            <h1 class="hero-title">Gaming Trivia Quest</h1>
            <p class="hero-subtitle">Test your gaming knowledge and compete with players worldwide</p>
            <div class="cta-buttons">
            <button class="cta-button primary-button" onclick="window.location.href='quiz_selection.php'">Play Now</button>
                <button class="cta-button secondary-button" onclick="window.location.href='about.php'">Learn More</button>
            </div>
        </div>
    </div>

    <script>
        <?php echo "
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('a[href = #]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    document.querySelector(this.getAttribute('href')).scrollIntoView({
                        behavior: 'smooth'
                    });
                });
            });

            const cards = document.querySelectorAll('.category-card');
            cards.forEach(card => {
                card.addEventListener('mouseenter', () => {
                    card.style.transform = 'translateY(-10px)';
                });
                card.addEventListener('mouseleave', () => {
                    card.style.transform = 'translateY(0)';
                });
            });
        });
        "; ?>
    </script>
</body>
</html>
