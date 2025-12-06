<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Student LMS</title>
    <!-- Bootstrap CSS for Navigation -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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
        
        .register-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }
        
        .register-card {
            background: white;
            padding: 40px;
            border: 3px solid #333;
            border-radius: 5px;
            box-shadow: 5px 5px 10px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 500px;
            text-align: center;
        }
        
        .register-icon {
            font-size: 50px;
            margin-bottom: 15px;
        }
        
        .register-title {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 8px;
            color: #333;
        }
        
        .register-subtitle {
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
            padding: 12px;
            border: 2px solid #999;
            border-radius: 3px;
            font-size: 14px;
        }
        
        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #1976d2;
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
            color: #1976d2;
            text-decoration: none;
        }
        
        .terms-checkbox a:hover {
            text-decoration: underline;
        }
        
        .register-btn {
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
        
        .register-btn:hover {
            background: #1565c0;
        }
        
        .login-link {
            color: #666;
            font-size: 13px;
        }
        
        .login-link a {
            color: #1976d2;
            text-decoration: none;
            font-weight: bold;
        }
        
        .login-link a:hover {
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
            
            .register-card {
                padding: 30px;
                margin: 20px;
            }
            
            .register-title {
                font-size: 24px;
            }
            
            .form-row {
                grid-template-columns: 1fr;
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
            <a href="<?= base_url('login') ?>">Login</a>
        </div>
    </nav>

    <!-- Register Container -->
    <div class="register-container">
        <div class="register-card">
            <div class="register-icon">📖</div>
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
                        <input type="password" id="password" name="password" placeholder="Create a password (min. 8 chars, with uppercase, lowercase, number, and special char)" required>
                        <small class="form-text text-muted" style="display: block; margin-top: 5px; font-size: 12px; color: #666;">
                            Password must be at least 8 characters and contain: uppercase letter, lowercase letter, number, and special character (@$!%*?&)
                        </small>
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
