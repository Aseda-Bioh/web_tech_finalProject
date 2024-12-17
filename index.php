<?php
// Signup Page: TriviaQuest
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
    <style>
        <?php echo "
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: url('Glitter_art.jpeg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-color: #1a1f2e;
            padding: 20px;
        }

        .signup-container {
            display: flex;
            background: rgba(37, 43, 72, 0.85);
            padding: 2.5rem;
            border-radius: 24px;
            width: 100%;
            max-width: 480px;
            box-shadow: 0 8px 32px rgba(0, 255, 0, 0.2);
            border: 1px solid rgba(0, 255, 0, 0.1);
            backdrop-filter: blur(10px);
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 2rem;
        }

        .logo-circle {
            width: 24px;
            height: 24px;
            background: #00FF00;
            border-radius: 50%;
            box-shadow: 0 0 15px rgba(0, 255, 0, 0.5);
        }

        .logo-text {
            color: white;
            font-size: 1.1rem;
        }

        h1 {
            color: white;
            font-size: 2rem;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .subtitle {
            color: #7a8194;
            margin-bottom: 2rem;
        }

        .name-fields {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .input-group {
            margin-bottom: 1rem;
        }

        input {
            width: 100%;
            padding: 14px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(0, 255, 0, 0.2);
            border-radius: 12px;
            color: white;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        input:focus {
            outline: none;
            border-color: #00FF00;
            background: rgba(255, 255, 255, 0.08);
        }

        .password-input {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #7a8194;
            cursor: pointer;
            padding: 5px;
        }

        .toggle-password:hover {
            color: #00FF00;
        }

        .buttons {
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 1rem;
            margin-top: 2rem;
        }

        button {
            padding: 14px 24px;
            border-radius: 12px;
            border: none;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .buttons button:hover {
            transform: translateY(-1px);
        }

        .secondary {
            background: rgba(255, 255, 255, 0.05);
            color: white;
            border: 1px solid rgba(0, 255, 0, 0.2);
        }

        .secondary:hover {
            background: rgba(255, 255, 255, 0.1);
            box-shadow: 0 0 15px rgba(0, 255, 0, 0.2);
        }

        .primary {
            background: #00FF00;
            color: black;
        }

        .primary:hover {
            background: #00CC00;
            box-shadow: 0 0 20px rgba(0, 255, 0, 0.4);
        }

        .error {
            color: #ff4444;
            font-size: 0.9rem;
            margin-top: 0.5rem;
            display: none;
        }

        .login-link {
            color: #00FF00;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .login-link:hover {
            color: #00CC00;
            text-shadow: 0 0 10px rgba(0, 255, 0, 0.3);
        }

        @media (max-width: 480px) {
            .signup-container {
                padding: 1.5rem;
            }

            .name-fields {
                grid-template-columns: 1fr;
            }

            .buttons {
                grid-template-columns: 1fr;
            }
        }
        "; ?>
    </style>
</head>
<body>
    <div class="signup-container">
        <div class="content-wrapper">
            <div class="logo">
                <div class="logo-circle"></div>
                <span class="logo-text">TriviaQuest</span>
            </div>
            
            <h1>Create new account</h1>
            <p class="subtitle">Already have an account? <a href="login.php" class="login-link">Log in</a></p>
            
            <form id="signup-form">
                <div class="name-fields">
                    <div class="input-group">
                        <input type="text" name="firstname" id="firstname" placeholder="First name" required>
                        <div class="error" id="firstname-error">Please enter your first name</div>
                    </div>
                    <div class="input-group">
                        <input type="text" name="lastname" id="lastname" placeholder="Last name" required>
                        <div class="error" id="lastname-error">Please enter your last name</div>
                    </div>
                </div>
                
                <div class="input-group">
                    <input type="email" name="email" id="email" placeholder="Email address" required>
                    <div class="error" id="email-error">Please enter a valid email address</div>
                </div>
                
                <div class="input-group">
                    <div class="password-input">
                        <input type="password" name="password" id="password" placeholder="Password" required>
                        <button type="button" class="toggle-password" id="toggle-password">Show</button>
                    </div>
                    <div class="error" id="password-error">Password must be at least 8 characters</div>
                </div>
                    
                <div class="buttons">
                    <button type="button" class="secondary">Change method</button>
                    <button type="submit" class="primary">Create account</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        <?php echo "
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('signup-form');
            const togglePassword = document.getElementById('toggle-password');
            const passwordInput = document.getElementById('password');
            
            togglePassword.addEventListener('click', () => {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                togglePassword.textContent = type === 'password' ? 'Show' : 'Hide';
            });
            
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                let isValid = true;
                
                const firstname = document.getElementById('firstname');
                const firstnameError = document.getElementById('firstname-error');
                if (!firstname.value.trim()) {
                    firstnameError.style.display = 'block';
                    isValid = false;
                } else {
                    firstnameError.style.display = 'none';
                }
                
                const lastname = document.getElementById('lastname');
                const lastnameError = document.getElementById('lastname-error');
                if (!lastname.value.trim()) {
                    lastnameError.style.display = 'block';
                    isValid = false;
                } else {
                    lastnameError.style.display = 'none';
                }
                
                const email = document.getElementById('email');
                const emailError = document.getElementById('email-error');
                const emailRegex = /^[^\\s@]+@[^\\s@]+\\.[^\\s@]+$/;
                if (!emailRegex.test(email.value)) {
                    emailError.style.display = 'block';
                    isValid = false;
                } else {
                    emailError.style.display = 'none';
                }
                
                const password = document.getElementById('password');
                const passwordError = document.getElementById('password-error');
                if (password.value.length < 8) {
                    passwordError.style.display = 'block';
                    isValid = false;
                } else {
                    passwordError.style.display = 'none';
                }
                
                if (isValid) {
                    console.log('Form submitted successfully!', {
                        firstname: firstname.value,
                        lastname: lastname.value,
                        email: email.value,
                        password: password.value
                    });
                }
            });
        });

        document.getElementById('signup-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const formData = new FormData(e.target);
            
            try {
                const response = await fetch('signupAction.php', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    alert(data.message); // Show success message
                    window.location.href = data.redirect; // Redirect to login page
                } else {
                    alert(data.message);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            }
        });
        "; ?>
    </script>
</body>
</html>
