<?php
require_once 'config.php';
if (is_logged_in()) {
    header('Location: app.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HopeLine — Join Us</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,600&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #7c9885;
            --primary-deep: #4d6b55;
            --bg: #faf8f4;
            --card-bg: #ffffff;
            --text-main: #2d2d2d;
            --text-soft: #6b6b6b;
            --white: #ffffff;
            --danger: #c0392b;
            --radius: 24px;
            --shadow: 0 10px 40px rgba(124, 152, 133, 0.12);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg);
            color: var(--text-main);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .bg-blob {
            position: absolute;
            width: 800px;
            height: 800px;
            background: radial-gradient(circle, rgba(124, 152, 133, 0.1) 0%, transparent 70%);
            border-radius: 50%;
            z-index: -1;
            filter: blur(60px);
        }

        .auth-card {
            background: var(--card-bg);
            padding: 48px;
            border-radius: var(--radius);
            width: 100%;
            max-width: 440px;
            box-shadow: var(--shadow);
            border: 1px solid rgba(124, 152, 133, 0.1);
            animation: slideUp 0.6s ease;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .auth-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .auth-header .logo {
            font-size: 32px;
            margin-bottom: 8px;
            display: block;
        }

        .auth-header h1 {
            font-family: 'Lora', serif;
            color: var(--primary-deep);
            font-size: 28px;
        }

        .tabs {
            display: flex;
            background: #f0f0ea;
            padding: 6px;
            border-radius: 12px;
            margin-bottom: 24px;
        }

        .tab {
            flex: 1;
            padding: 10px;
            border: none;
            background: transparent;
            font-family: 'Outfit', sans-serif;
            font-size: 14px;
            font-weight: 600;
            color: var(--text-soft);
            cursor: pointer;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .tab.active {
            background: var(--white);
            color: var(--primary-deep);
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-soft);
            margin-bottom: 6px;
        }

        .form-group input {
            width: 100%;
            padding: 12px 16px;
            border: 1.5px solid #ede4d3;
            border-radius: 12px;
            font-family: 'Outfit', sans-serif;
            font-size: 15px;
            outline: none;
            transition: all 0.3s;
        }

        .form-group input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(124, 152, 133, 0.1);
        }

        .btn-submit {
            width: 100%;
            padding: 14px;
            background: var(--primary);
            color: var(--white);
            border: none;
            border-radius: 12px;
            font-family: 'Outfit', sans-serif;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 10px;
        }

        .btn-submit:hover {
            background: var(--primary-deep);
            transform: translateY(-2px);
        }

        .auth-msg {
            margin-top: 16px;
            padding: 12px;
            border-radius: 10px;
            font-size: 13px;
            display: none;
            text-align: center;
        }

        .auth-msg.error { background: #fdf0ee; color: var(--danger); border: 1px solid #f9d6d1; }
        .auth-msg.success { background: #e8f0ea; color: var(--primary-deep); border: 1px solid #c9d8ce; }

        .back-home {
            display: block;
            text-align: center;
            margin-top: 24px;
            color: var(--text-soft);
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s;
        }

        .back-home:hover { color: var(--primary); }
    </style>
</head>
<body>

    <div class="bg-blob"></div>

    <div class="auth-card">
        <div class="auth-header">
            <span class="logo">🌿</span>
            <h1>HopeLine</h1>
            <p style="color: var(--text-soft); font-size: 14px; margin-top: 4px;">You are not alone.</p>
        </div>

        <div class="tabs">
            <button class="tab active" onclick="switchTab('login')">Login</button>
            <button class="tab" onclick="switchTab('register')">Register</button>
        </div>

        <!-- Login Form -->
        <form id="login-form">
            <div class="form-group">
                <label>Username or Email</label>
                <input type="text" name="identifier" name="identifier" required placeholder="Enter username or email...">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required placeholder="••••••••">
            </div>
            <button type="submit" class="btn-submit">Sign In</button>
        </form>

        <!-- Register Form -->
        <form id="register-form" style="display: none;">
            <div class="form-group">
                <label>Username (Anonymous)</label>
                <input type="text" name="username" required placeholder="e.g. HopeGiver123">
            </div>
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" required placeholder="you@example.com">
            </div>
            <div class="form-group">
                <label>Create Password</label>
                <input type="password" name="password" required placeholder="Min 6 characters">
            </div>
            <button type="submit" class="btn-submit">Create Account</button>
        </form>

        <div id="auth-msg" class="auth-msg"></div>

        <a href="index.php" class="back-home">← Back to Home</a>
    </div>

    <script>
        function switchTab(mode) {
            const loginForm = document.getElementById('login-form');
            const registerForm = document.getElementById('register-form');
            const tabs = document.querySelectorAll('.tab');
            const msgEl = document.getElementById('auth-msg');
            
            msgEl.style.display = 'none';

            if (mode === 'login') {
                loginForm.style.display = 'block';
                registerForm.style.display = 'none';
                tabs[0].classList.add('active');
                tabs[1].classList.remove('active');
            } else {
                loginForm.style.display = 'none';
                registerForm.style.display = 'block';
                tabs[0].classList.remove('active');
                tabs[1].classList.add('active');
            }
        }

        async function handleAuth(event, action) {
            event.preventDefault();
            const formData = new FormData(event.target);
            formData.append('action', action);

            const msgEl = document.getElementById('auth-msg');
            const submitBtn = event.target.querySelector('button');
            
            submitBtn.disabled = true;
            submitBtn.textContent = 'Processing...';

            try {
                const response = await fetch('auth.php', {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();

                msgEl.style.display = 'block';
                msgEl.textContent = data.message;
                msgEl.className = 'auth-msg ' + (data.success ? 'success' : 'error');

                if (data.success) {
                    setTimeout(() => {
                        window.location.href = 'app.php';
                    }, 1000);
                }
            } catch (error) {
                msgEl.style.display = 'block';
                msgEl.textContent = 'An error occurred. Please try again.';
                msgEl.className = 'auth-msg error';
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = action === 'login' ? 'Sign In' : 'Create Account';
            }
        }

        document.getElementById('login-form').onsubmit = (e) => handleAuth(e, 'login');
        document.getElementById('register-form').onsubmit = (e) => handleAuth(e, 'register');
    </script>
</body>
</html>
