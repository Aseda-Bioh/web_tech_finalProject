<?php
// Login Page: TriviaQuest
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TriviaQuest - Login</title>
    <style>
        <?php echo "
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
            background: url('login_bg.jpeg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-color: #000;
            padding: 20px;
            position: relative;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at center, rgba(0, 255, 0, 0.1), transparent 70%);
            pointer-events: none;
        }

        .login-container {
            background: rgba(13, 17, 23, 0.95);
            padding: 2rem;
            border-radius: 24px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 8px 32px rgba(0, 255, 0, 0.2);
            border: 1px solid rgba(0, 255, 0, 0.1);
            backdrop-filter: blur(10px);
            position: relative;
            z-index: 1;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 1.5rem;
        }

        .logo-circle {
            width: 24px;
            height: 24px;
            background: #00FF00;
            border-radius: 50%;
            box-shadow: 0 0 15px rgba(0, 255, 0, 0.5);
        }

        .logo-text {
            color: #00FF00;
            font-size: 1.5rem;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            text-shadow: 0 0 10px rgba(0, 255, 0, 0.3);
        }

        h1 {
            color: white;
            font-size: 2.2rem;
            margin-bottom: 0.5rem;
            text-shadow: 0 0 10px rgba(0, 255, 0, 0.3);
        }

        .subtitle {
            color: #888;
            margin-bottom: 1.5rem;
            font-size: 0.95rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            color: #00FF00;
            margin-bottom: 0.4rem;
            text-shadow: 0 0 5px rgba(0, 255, 0, 0.3);
            font-size: 0.9rem;
        }

        input {
            width: 100%;
            padding: 12px;
            background: rgba(0, 255, 0, 0.05);
            border: 1px solid rgba(0, 255, 0, 0.2);
            border-radius: 12px;
            color: white;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        input:focus {
            outline: none;
            border-color: #00FF00;
            box-shadow: 0 0 10px rgba(0, 255, 0, 0.2);
            background: rgba(0, 255, 0, 0.1);
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 1rem 0;
            color: #888;
        }

        #remember {
            width: 16px;
            height: 16px;
            accent-color: #00FF00;
        }

        .checkbox-group label {
            font-size: 0.9rem;
        }

        .login-button {
            width: 100%;
            padding: 12px;
            background: #00FF00;
            border: none;
            border-radius: 12px;
            color: black;
            font-size: 1rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .login-button:hover {
            background: #00CC00;
            box-shadow: 0 0 20px rgba(0, 255, 0, 0.4);
            transform: translateY(-2px);
        }

        .error-message {
            color: #ff4444;
            font-size: 0.85rem;
            margin-top: 0.3rem;
            display: none;
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 1.5rem;
            }
            
            h1 {
                font-size: 2rem;
            }
        }
        "; ?>
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <div class="logo-circle"></div>
            <span class="logo-text">TriviaQuest</span>
        </div>

        <h1>Welcome Back!</h1>
        <p class="subtitle">Enter your credentials to access your account and join the adventure.</p>

        <form id="login-form">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required> 
                <div id="email-error" class="error-message">Please enter a valid email address</div>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required> 
                <div id="password-error" class="error-message">Password is required</div>
            </div>

            <div class="checkbox-group">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Remember me</label>
            </div>

            <button type="submit" class="login-button">Sign In</button>
        </form>
    </div>

    <script>
        <?php echo "
            document.getElementById('login-form').addEventListener('submit', async (e) => {
                e.preventDefault();
                let isValid = true;
                
                const email = document.getElementById('email');
                const emailError = document.getElementById('email-error');
                const password = document.getElementById('password');
                const passwordError = document.getElementById('password-error');
                
                // Reset error messages
                emailError.style.display = 'none';
                passwordError.style.display = 'none';
                
                // Basic validation
                if (!email.value) {
                    emailError.textContent = 'Email is required';
                    emailError.style.display = 'block';
                    isValid = false;
                }
                
                if (!password.value) {
                    passwordError.style.display = 'block';
                    isValid = false;
                }
                
                if (isValid) {
                    const formData = new FormData(e.target);
                    
                    try {
                        const response = await fetch('loginAction.php', {
                            method: 'POST',
                            body: formData
                        });
                        
                        const data = await response.json();
                        
                        if (data.success) {
                            window.location.href = data.redirect; // Redirect to home page
                        } else {
                            // Show error message near the relevant field
                            if (data.message.toLowerCase().includes('email')) {
                                emailError.textContent = data.message;
                                emailError.style.display = 'block';
                            } else if (data.message.toLowerCase().includes('password')) {
                                passwordError.textContent = data.message;
                                passwordError.style.display = 'block';
                            } else {
                                alert(data.message);
                            }
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('An error occurred. Please try again.');
                    }
                }
            });";?>
    </script>
</body>
</html>
