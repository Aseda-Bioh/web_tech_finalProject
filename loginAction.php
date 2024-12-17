<?php
session_start();
header('Content-Type: application/json');

// Database connection configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'kwabena.bioh');
define('DB_PASS', 'FatherAbraham2');
define('DB_NAME', 'webtech_fall2024_kwabena_bioh');

// Response array
$response = [
    'success' => false,
    'message' => '',
    'redirect' => ''
];

try {
    // Create database connection
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    // Check connection
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Check if the request is POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get and sanitize input
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'] ?? '';
        $remember = isset($_POST['remember']) ? true : false;

        // Validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Invalid email format');
        }

        // Validate password
        if (empty($password)) {
            throw new Exception('Password is required');
        }

        // Prepare SQL statement
        $stmt = $conn->prepare("SELECT user_id, first_name, last_name, password_hash, current_level, total_points 
                              FROM users 
                              WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            throw new Exception('Invalid email or password');
        }

        $user = $result->fetch_assoc();

        // Verify password
        if (!password_verify($password, $user['password_hash'])) {
            throw new Exception('Invalid email or password');
        }

        // Update last login timestamp
        $updateStmt = $conn->prepare("UPDATE users SET last_login = CURRENT_TIMESTAMP WHERE user_id = ?");
        $updateStmt->bind_param("i", $user['user_id']);
        $updateStmt->execute();

        // Set session variables
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
        $_SESSION['user_level'] = $user['current_level'];
        $_SESSION['total_points'] = $user['total_points'];
        $_SESSION['logged_in'] = true;

        // Handle remember me functionality
        if ($remember) {
            $token = bin2hex(random_bytes(32));
            $token_hash = hash('sha256', $token);
            $expiry = date('Y-m-d H:i:s', strtotime('+30 days'));

            $tokenStmt = $conn->prepare("INSERT INTO user_tokens (user_id, token_hash, expiry) VALUES (?, ?, ?)");
            $tokenStmt->bind_param("iss", $user['user_id'], $token_hash, $expiry);
            $tokenStmt->execute();

            setcookie('remember_token', $token, [
                'expires' => strtotime('+30 days'),
                'path' => '/',
                'secure' => true,
                'httponly' => true,
                'samesite' => 'Strict'
            ]);
        }

        // Log the successful login
        $activityStmt = $conn->prepare("INSERT INTO activity_log (user_id, activity_type, description) 
                                      VALUES (?, 'First_Time', 'User logged in successfully')");
        $activityStmt->bind_param("i", $user['user_id']);
        $activityStmt->execute();

        $response['success'] = true;
        $response['message'] = 'Login successful';
        $response['redirect'] = 'home.php';

    } else {
        throw new Exception('Invalid request method');
    }

} catch (Exception $e) {
    $response['success'] = false;
    $response['message'] = $e->getMessage();
} finally {
    // Close database connection if it exists
    if (isset($conn)) {
        $conn->close();
    }
}

// Send JSON response
echo json_encode($response);