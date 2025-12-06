<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Student LMS</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            background: #4a90e2;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .navbar {
            background: white;
            padding: 15px 30px;
            border-bottom: 2px solid #ccc;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            font-size: 22px;
            font-weight: bold;
            color: #1976d2;
        }
        
        .nav-links {
            display: flex;
            gap: 20px;
        }
        
        .nav-links a {
            text-decoration: none;
            color: #333;
            font-weight: bold;
        }
        
        .nav-links a:hover {
            color: #1976d2;
        }
        
        .login-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }
        
        .login-card {
            background: white;
            padding: 40px;
            border: 3px solid #333;
            border-radius: 5px;
            box-shadow: 5px 5px 10px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 450px;
            text-align: center;
        }
        
        .login-icon {
            font-size: 50px;
            margin-bottom: 15px;
        }
        
        .login-title {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 8px;
            color: #333;
        }
        
        .login-subtitle {
            color: #666;
            margin-bottom: 30px;
            font-size: 14px;
        }
        
        .alert {
            padding: 12px;
            border-radius: 3px;
            margin-bottom: 20px;
            text-align: left;
            display: flex;
            align-items: center;
            border: 2px solid;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border-color: #c3e6cb;
        }
        
        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
        }
        
        .alert-icon {
            font-size: 18px;
            margin-right: 10px;
        }
        
        .form-group {
            margin-bottom: 25px;
            text-align: left;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
            font-size: 14px;
        }
        
        .form-group input {
            width: 100%;
            padding: 12px;
            border: 2px solid #999;
            border-radius: 3px;
            font-size: 14px;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #1976d2;
        }
        
        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            font-size: 13px;
        }
        
        .remember-me {
            display: flex;
            align-items: center;
        }
        
        .remember-me input {
            margin-right: 6px;
        }
        
        .forgot-password {
            color: #1976d2;
            text-decoration: none;
            font-weight: bold;
        }
        
        .forgot-password:hover {
            text-decoration: underline;
        }
        
        .login-btn {
            width: 100%;
            background: #1976d2;
            color: white;
            border: 2px solid #1565c0;
            padding: 15px;
            border-radius: 3px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            margin-bottom: 20px;
        }
        
        .login-btn:hover {
            background: #1565c0;
        }
        
        .signup-link {
            color: #666;
            font-size: 13px;
        }
        
        .signup-link a {
            color: #1976d2;
            text-decoration: none;
            font-weight: bold;
        }
        
        .signup-link a:hover {
            text-decoration: underline;
        }
        
        .footer-links {
            margin-top: 25px;
            padding-top: 15px;
            border-top: 2px solid #ccc;
            display: flex;
            justify-content: center;
            gap: 15px;
            font-size: 12px;
        }
        
        .footer-links a {
            color: #666;
            text-decoration: none;
        }
        
        .footer-links a:hover {
            color: #1976d2;
        }
        
        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }
            
            .login-card {
                padding: 25px;
                margin: 15px;
            }
            
            .login-title {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="logo">Student LMS</div>
        <div class="nav-links">
            <a href="<?= base_url() ?>">Home</a>
            <a href="<?= base_url('about') ?>">About</a>
            <a href="<?= base_url('contact') ?>">Contact</a>
            <a href="<?= base_url('register') ?>">Register</a>
        </div>
    </nav>

    <!-- Login Container -->
    <div class="login-container">
        <div class="login-card">
            <div class="login-icon">📖</div>
            <h1 class="login-title">Welcome Back!</h1>
            <p class="login-subtitle">Sign in to continue your learning journey</p>

            <!-- Flash Messages -->
            <?php if(session()->getFlashdata('error')): ?>
                <div class="alert alert-danger">
                    <span class="alert-icon">⚠️</span>
                    <span><?= session()->getFlashdata('error') ?></span>
                </div>
            <?php endif; ?>

            <?php if(session()->getFlashdata('success')): ?>
                <div class="alert alert-success">
                    <span class="alert-icon">✅</span>
                    <span><?= session()->getFlashdata('success') ?></span>
                </div>
            <?php endif; ?>

            <!-- Login Form -->
            <form method="post" action="<?= base_url('login') ?>">
                <?= csrf_field() ?>
                
                <div class="form-group">
                    <label for="login">Email or Username</label>
                    <input type="text" id="login" name="login" value="<?= old('login') ?>" placeholder="Enter your email or username" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                </div>

                <div class="form-options">
                    <label class="remember-me">
                        <input type="checkbox" name="remember_me">
                        Remember me
                    </label>
                    <a href="#" class="forgot-password">Forgot password?</a>
                </div>

                <button type="submit" class="login-btn">Sign In</button>
            </form>

            <!-- Sign Up Link -->
            <p class="signup-link">
                Don't have an account? <a href="<?= base_url('register') ?>">Sign up for free</a>
            </p>

            <!-- Footer Links -->
            <div class="footer-links">
                <a href="<?= base_url() ?>">Home</a>
                <a href="<?= base_url('about') ?>">About</a>
                <a href="<?= base_url('contact') ?>">Contact</a>
                <a href="#">Help</a>
            </div>
        </div>
    </div>
</body>
</html>
