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

// Fetch active categories
$query = "SELECT * FROM categories WHERE is_active = 1";
$categories = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Selection</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #0a0a0a;
            color: white;
            padding: 20px;
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

        .container {
            text-align: center;
            width: 100%;
            max-width: 960px;
            margin-top: 80px;
        }

        .title {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: #00FF00;
            text-shadow: 0 0 10px rgba(0, 255, 0, 0.5);
        }

        .subtitle {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            color: #b0b0b0;
        }

        .categories {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-top: 1rem;
        }

        .category-card {
            background: rgba(13, 17, 23, 0.95);
            border: 1px solid rgba(0, 255, 0, 0.2);
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .category-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 255, 0, 0.1);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .category-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 255, 0, 0.4);
            border-color: #00FF00;
        }

        .category-card:hover::before {
            opacity: 1;
        }

        .category-icon {
            font-size: 3rem;
            color: #00FF00;
            margin-bottom: 1rem;
        }

        .category-name {
            font-size: 1.2rem;
            color: white;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .category-description {
            font-size: 0.9rem;
            color: #888;
            margin-top: 0.5rem;
            line-height: 1.4;
        }

        .difficulty-badge {
            position: absolute;
            top: 1rem;
            right: 1rem;
            padding: 0.3rem 0.8rem;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: bold;
            background: rgba(0, 255, 0, 0.1);
            color: #00FF00;
            border: 1px solid rgba(0, 255, 0, 0.3);
        }

        @media (max-width: 480px) {
            .title {
                font-size: 2rem;
            }

            .subtitle {
                font-size: 1rem;
            }

            .nav-links {
                display: none;
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

    <div class="container">
        <h1 class="title">Select a Quiz Category</h1>
        <p class="subtitle">Choose a category to test your gaming knowledge and start the challenge!</p>
        <div class="categories">
            <?php while($category = $categories->fetch_assoc()): ?>
                <div class="category-card" onclick="startQuiz(<?php echo $category['category_id']; ?>, '<?php echo $category['name']; ?>')">
                    <div class="category-icon">
                        <i class="fas <?php echo htmlspecialchars($category['icon_class']); ?>"></i>
                    </div>
                    <div class="category-name"><?php echo htmlspecialchars($category['name']); ?></div>
                    <p class="category-description"><?php echo htmlspecialchars($category['description']); ?></p>
                    <span class="difficulty-badge"><?php echo $category['difficulty_level']; ?></span>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <script>
        function startQuiz(categoryId, categoryName) {
            // Map category IDs to their respective PHP files
            const categoryPages = {
                1: 'quiz_retro.php',
                2: 'quiz_rpg.php',
                3: 'quiz_fps.php',
                4: 'quiz_esports.php'
            };

            // Get the corresponding quiz page
            const quizPage = categoryPages[categoryId];

            if (quizPage) {
                window.location.href = `${quizPage}?category=${categoryId}`;
            } else {
                console.error('Quiz page not found for category:', categoryName);
            }
        }

        // Add hover effects to category cards
        document.querySelectorAll('.category-card').forEach(card => {
            card.addEventListener('mouseenter', () => {
                card.style.transform = 'translateY(-5px)';
            });
            card.addEventListener('mouseleave', () => {
                card.style.transform = 'translateY(0)';
            });
        });
    </script>
</body>
</html>
<?php $conn->close(); ?>