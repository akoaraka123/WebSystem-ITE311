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
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .navbar {
            background: white;
            padding: 15px 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #667eea;
        }
        
        .nav-links {
            display: flex;
            gap: 25px;
        }
        
        .nav-links a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
            transition: color 0.3s;
        }
        
        .nav-links a:hover {
            color: #667eea;
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
            padding: 50px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 450px;
            text-align: center;
            animation: fadeInUp 0.8s ease;
        }
        
        .login-icon {
            font-size: 64px;
            margin-bottom: 20px;
            color: #667eea;
        }
        
        .login-title {
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }
        
        .login-subtitle {
            color: #666;
            margin-bottom: 40px;
            font-size: 16px;
        }
        
        .alert {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 25px;
            text-align: left;
            display: flex;
            align-items: center;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .alert-icon {
            font-size: 20px;
            margin-right: 12px;
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
            padding: 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            font-size: 14px;
        }
        
        .remember-me {
            display: flex;
            align-items: center;
        }
        
        .remember-me input {
            margin-right: 8px;
        }
        
        .forgot-password {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
        }
        
        .forgot-password:hover {
            text-decoration: underline;
        }
        
        .login-btn {
            width: 100%;
            background: #667eea;
            color: white;
            border: none;
            padding: 18px;
            border-radius: 10px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.3s, box-shadow 0.3s;
            margin-bottom: 25px;
        }
        
        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }
        
        .divider {
            text-align: center;
            margin: 30px 0;
            position: relative;
        }
        
        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #e0e0e0;
        }
        
        .divider span {
            background: white;
            padding: 0 20px;
            color: #666;
            font-size: 14px;
        }
        
        .social-login {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 30px;
        }
        
        .social-btn {
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            background: white;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 14px;
            font-weight: 500;
        }
        
        .social-btn:hover {
            border-color: #667eea;
            transform: translateY(-2px);
        }
        
        .signup-link {
            color: #666;
            font-size: 14px;
        }
        
        .signup-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }
        
        .signup-link a:hover {
            text-decoration: underline;
        }
        
        .footer-links {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
            display: flex;
            justify-content: center;
            gap: 20px;
            font-size: 13px;
        }
        
        .footer-links a {
            color: #666;
            text-decoration: none;
        }
        
        .footer-links a:hover {
            color: #667eea;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }
            
            .login-card {
                padding: 30px;
                margin: 20px;
            }
            
            .login-title {
                font-size: 28px;
            }
            
            .social-login {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="logo">📚 Student LMS</div>
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
            <div class="login-icon">🎓</div>
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

            <!-- Social Login -->
            <div class="divider">
                <span>Or continue with</span>
            </div>
            
            <div class="social-login">
                <button class="social-btn">
                    📧 Google
                </button>
                <button class="social-btn">
                    📘 Facebook
                </button>
            </div>

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
