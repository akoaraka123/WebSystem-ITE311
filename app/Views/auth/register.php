<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Student LMS</title>
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
        
        .register-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }
        
        .register-card {
            background: white;
            padding: 50px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 500px;
            text-align: center;
            animation: fadeInUp 0.8s ease;
        }
        
        .register-icon {
            font-size: 64px;
            margin-bottom: 20px;
            color: #667eea;
        }
        
        .register-title {
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }
        
        .register-subtitle {
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
            margin-bottom: 20px;
            text-align: left;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
            font-size: 14px;
        }
        
        .form-group input,
        .form-group select {
            width: 100%;
            padding: 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        
        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        
        .terms-checkbox {
            display: flex;
            align-items: flex-start;
            margin-bottom: 25px;
            text-align: left;
        }
        
        .terms-checkbox input {
            margin-right: 10px;
            margin-top: 2px;
        }
        
        .terms-checkbox label {
            font-size: 14px;
            color: #666;
            line-height: 1.4;
        }
        
        .terms-checkbox a {
            color: #667eea;
            text-decoration: none;
        }
        
        .terms-checkbox a:hover {
            text-decoration: underline;
        }
        
        .register-btn {
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
        
        .register-btn:hover {
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
        
        .social-register {
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
        
        .login-link {
            color: #666;
            font-size: 14px;
        }
        
        .login-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }
        
        .login-link a:hover {
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
            
            .register-card {
                padding: 30px;
                margin: 20px;
            }
            
            .register-title {
                font-size: 28px;
            }
            
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .social-register {
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
            <a href="<?= base_url('login') ?>">Login</a>
        </div>
    </nav>

    <!-- Register Container -->
    <div class="register-container">
        <div class="register-card">
            <div class="register-icon">🎓</div>
            <h1 class="register-title">Join Student LMS!</h1>
            <p class="register-subtitle">Create your free account and start learning today</p>

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

            <?php if(session()->getFlashdata('errors')): ?>
                <div class="alert alert-danger">
                    <span class="alert-icon">⚠️</span>
                    <div>
                        <?php foreach(session()->getFlashdata('errors') as $error): ?>
                            <p><?= esc($error) ?></p>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Register Form -->
            <form method="post" action="<?= base_url('register') ?>">
                <?= csrf_field() ?>
                
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" value="<?= old('name') ?>" placeholder="Enter your full name" required>
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" value="<?= old('email') ?>" placeholder="Enter your email address" required>
                </div>

                <div class="form-group">
                    <label for="role">I am a</label>
                    <select id="role" name="role" required>
                        <option value="">Select your role</option>
                        <option value="student" <?= old('role') == 'student' ? 'selected' : '' ?>>Student</option>
                        <option value="teacher" <?= old('role') == 'teacher' ? 'selected' : '' ?>>Teacher</option>
                    </select>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="Create a password" required>
                    </div>

                    <div class="form-group">
                        <label for="password_confirm">Confirm Password</label>
                        <input type="password" id="password_confirm" name="password_confirm" placeholder="Confirm password" required>
                    </div>
                </div>

                <div class="terms-checkbox">
                    <input type="checkbox" id="terms" name="terms" required>
                    <label for="terms">
                        I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>
                    </label>
                </div>

                <button type="submit" class="register-btn">Create Account</button>
            </form>

            <!-- Social Register -->
            <div class="divider">
                <span>Or register with</span>
            </div>
            
            <div class="social-register">
                <button class="social-btn">
                    📧 Google
                </button>
                <button class="social-btn">
                    📘 Facebook
                </button>
            </div>

            <!-- Login Link -->
            <p class="login-link">
                Already have an account? <a href="<?= base_url('login') ?>">Sign in here</a>
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
