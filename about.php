<?php
// Database connection
define('DB_HOST', 'localhost');
define('DB_USER', 'kwabena.bioh');
define('DB_PASS', 'FatherAbraham2');
define('DB_NAME', 'webtech_fall2024_kwabena_bioh');

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch categories with their stats
$query = "
    SELECT c.*, 
           COUNT(q.question_id) as total_questions,
           COUNT(DISTINCT l.user_id) as total_players,
           AVG(l.score) as average_score
    FROM categories c
    LEFT JOIN questions q ON c.category_id = q.category_id
    LEFT JOIN leaderboards l ON c.category_id = l.category_id
    WHERE c.is_active = 1
    GROUP BY c.category_id
";

$categories = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About - Gaming Trivia Quest</title>
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
            text-decoration: none;
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

        .hero-section {
            margin-top: 80px;
            padding: 4rem 2rem;
            text-align: center;
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('gaming_bg.jpg');
            background-size: cover;
            background-position: center;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at center, rgba(0,255,0,0.1), transparent 70%);
        }

        .hero-content {
            position: relative;
            z-index: 1;
            max-width: 800px;
            margin: 0 auto;
        }

        .hero-title {
            font-size: 3rem;
            color: #00ff00;
            margin-bottom: 1rem;
            text-shadow: 0 0 10px rgba(0,255,0,0.5);
        }

        .hero-description {
            font-size: 1.2rem;
            color: #b0b0b0;
            line-height: 1.6;
            margin-bottom: 2rem;
        }

        .main-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .section-title {
            font-size: 2rem;
            color: #00ff00;
            margin-bottom: 2rem;
            text-align: center;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid rgba(0,255,0,0.3);
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-bottom: 4rem;
        }

        .feature-card {
            background: rgba(0, 0, 0, 0.5);
            padding: 2rem;
            border-radius: 15px;
            text-align: center;
            border: 1px solid rgba(0,255,0,0.2);
            transition: transform 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            border-color: #00ff00;
        }

        .feature-icon {
            font-size: 2.5rem;
            color: #00ff00;
            margin-bottom: 1rem;
        }

        .categories-section {
            margin-top: 4rem;
        }

        .category-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .category-card {
            background: rgba(0, 0, 0, 0.7);
            border-radius: 15px;
            overflow: hidden;
            border: 1px solid rgba(0,255,0,0.2);
            transition: all 0.3s ease;
        }

        .category-card:hover {
            transform: translateY(-10px);
            border-color: #00ff00;
            box-shadow: 0 5px 15px rgba(0,255,0,0.2);
        }

        .category-image {
            width: 100%;
            height: 200px;
            background-size: cover;
            background-position: center;
            border-bottom: 1px solid rgba(0,255,0,0.2);
        }

        .category-content {
            padding: 1.5rem;
        }

        .category-name {
            font-size: 1.5rem;
            color: #00ff00;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .category-description {
            color: #b0b0b0;
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }

        .category-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            text-align: center;
        }

        .stat-item {
            padding: 0.5rem;
            background: rgba(0,255,0,0.1);
            border-radius: 8px;
        }

        .stat-value {
            font-size: 1.2rem;
            color: #00ff00;
            font-weight: bold;
        }

        .stat-label {
            font-size: 0.8rem;
            color: #888;
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 2rem;
            }

            .hero-description {
                font-size: 1rem;
            }

            .nav-links {
                display: none;
            }

            .features-grid {
                grid-template-columns: 1fr;
            }

            .category-cards {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <nav>
        <a href="home.php" class="logo">TriviaQuest</a>
        <div class="nav-links">
            <a href="home.php">Home</a>
            <a href="leaderboard.php">Leaderboard</a>
            <a href="dashboard.php">Profile</a>
        </div>
    </nav>

    <div class="hero-section">
        <div class="hero-content">
            <h1 class="hero-title">Welcome to Gaming Trivia Quest</h1>
            <p class="hero-description">
                Embark on an epic journey through gaming history, test your knowledge, and compete with players worldwide in our 
                comprehensive gaming trivia platform. From retro classics to modern esports, challenge yourself across multiple 
                gaming categories and prove your expertise!
            </p>
        </div>
    </div>

    <div class="main-content">
        <h2 class="section-title">Key Features</h2>
        <div class="features-grid">
            <div class="feature-card">
                <i class="fas fa-trophy feature-icon"></i>
                <h3>Achievement System</h3>
                <p>Unlock achievements and earn rewards as you demonstrate your gaming knowledge</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-chart-line feature-icon"></i>
                <h3>Global Leaderboards</h3>
                <p>Compete with players worldwide and climb the rankings in each category</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-clock feature-icon"></i>
                <h3>Timed Challenges</h3>
                <p>Test your speed and accuracy with time-limited quiz sessions</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-level-up-alt feature-icon"></i>
                <h3>Progressive Difficulty</h3>
                <p>Face increasingly challenging questions as you improve</p>
            </div>
        </div>

        <div class="categories-section">
            <h2 class="section-title">Quiz Categories</h2>
            <div class="category-cards">
                <?php while($category = $categories->fetch_assoc()): ?>
                    <div class="category-card">
                        <div class="category-image" style="background-image: url('<?php echo htmlspecialchars($category['background_image_url']); ?>')"></div>
                        <div class="category-content">
                            <h3 class="category-name">
                                <i class="fas <?php echo htmlspecialchars($category['icon_class']); ?>"></i>
                                <?php echo htmlspecialchars($category['name']); ?>
                            </h3>
                            <p class="category-description">
                                <?php echo htmlspecialchars($category['description']); ?>
                            </p>
                            <div class="category-stats">
                                <div class="stat-item">
                                    <div class="stat-value"><?php echo $category['total_questions'] ?? 0; ?></div>
                                    <div class="stat-label">Questions</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-value"><?php echo $category['points_per_question']; ?></div>
                                    <div class="stat-label">Points/Q</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-value"><?php echo $category['difficulty_level']; ?></div>
                                    <div class="stat-label">Difficulty</div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Add hover effects to cards
            const cards = document.querySelectorAll('.category-card, .feature-card');
            cards.forEach(card => {
                card.addEventListener('mouseenter', () => {
                    card.style.transform = 'translateY(-10px)';
                });
                card.addEventListener('mouseleave', () => {
                    card.style.transform = 'translateY(0)';
                });
            });

            // Add smooth scrolling
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    document.querySelector(this.getAttribute('href')).scrollIntoView({
                        behavior: 'smooth'
                    });
                });
            });
        });
    </script>
</body>
</html>