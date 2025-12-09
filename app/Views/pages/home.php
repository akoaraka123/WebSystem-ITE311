<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student LMS - Home</title>
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
        
        .hero {
            text-align: center;
            padding: 60px 20px;
            color: white;
        }
        
        .hero h1 {
            font-size: 36px;
            margin-bottom: 15px;
        }
        
        .hero p {
            font-size: 18px;
            margin-bottom: 25px;
        }
        
        .btn-group {
            display: flex;
            gap: 15px;
            justify-content: center;
        }
        
        .btn {
            padding: 12px 25px;
            border: 2px solid;
            border-radius: 3px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-primary {
            background: white;
            color: #1976d2;
            border-color: white;
        }
        
        .btn-secondary {
            background: transparent;
            color: white;
            border-color: white;
        }
        
        .btn:hover {
            background: #f0f0f0;
        }
        
        .btn-secondary:hover {
            background: rgba(255,255,255,0.1);
        }
        
        .features {
            background: white;
            padding: 50px 20px;
            text-align: center;
        }
        
        .features h2 {
            font-size: 28px;
            margin-bottom: 30px;
            color: #333;
        }
        
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .feature-card {
            background: #f5f5f5;
            padding: 25px;
            border: 2px solid #999;
            border-radius: 3px;
        }
        
        .feature-icon {
            font-size: 40px;
            margin-bottom: 15px;
        }
        
        .feature-card h3 {
            font-size: 18px;
            margin-bottom: 10px;
            color: #333;
        }
        
        .feature-card p {
            color: #666;
            line-height: 1.5;
            font-size: 14px;
        }
        
        .cta {
            background: #1976d2;
            color: white;
            padding: 50px 20px;
            text-align: center;
            border-top: 3px solid #1565c0;
        }
        
        .cta h2 {
            font-size: 28px;
            margin-bottom: 15px;
        }
        
        .cta p {
            font-size: 16px;
            margin-bottom: 25px;
        }
        
        .footer {
            background: #333;
            color: white;
            padding: 30px 20px;
            text-align: center;
            border-top: 2px solid #555;
        }
        
        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            margin-bottom: 20px;
        }
        
        .footer-section h4 {
            margin-bottom: 12px;
            color: #1976d2;
        }
        
        .footer-section a {
            color: #ccc;
            text-decoration: none;
            display: block;
            margin-bottom: 6px;
        }
        
        .footer-section a:hover {
            color: white;
        }
        
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 28px;
            }
            
            .hero p {
                font-size: 16px;
            }
            
            .btn-group {
                flex-direction: column;
                align-items: center;
            }
            
            .nav-links {
                display: none;
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

    <!-- Hero Section -->
    <section class="hero">
        <h1>Welcome to Student LMS</h1>
        <p>Your gateway to amazing online learning experiences</p>
        <div class="btn-group">
            <a href="<?= base_url('login') ?>" class="btn btn-primary">Login</a>
            <a href="<?= base_url('about') ?>" class="btn btn-secondary">Learn More</a>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features">
        <h2>What We Offer</h2>
        <div class="feature-grid">
            <div class="feature-card">
                <div class="feature-icon">📚</div>
                <h3>Easy Learning</h3>
                <p>Access your courses anytime, anywhere with our simple and intuitive platform</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">👥</div>
                <h3>Connect with Teachers</h3>
                <p>Get help from your teachers and collaborate with classmates easily</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">📱</div>
                <h3>Mobile Friendly</h3>
                <p>Study on your phone, tablet, or computer - works on all devices</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">📊</div>
                <h3>Track Progress</h3>
                <p>See your grades and progress in real-time</p>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta">
        <h2>Ready to Start Learning?</h2>
        <p>Login to access your courses and continue your learning journey</p>
        <a href="<?= base_url('login') ?>" class="btn btn-primary">Login Now</a>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h4>Student LMS</h4>
                <p>Making online learning simple and fun for students</p>
            </div>
            <div class="footer-section">
                <h4>Quick Links</h4>
                <a href="<?= base_url() ?>">Home</a>
                <a href="<?= base_url('about') ?>">About</a>
                <a href="<?= base_url('contact') ?>">Contact</a>
            </div>
            <div class="footer-section">
                <h4>Account</h4>
                <a href="<?= base_url('login') ?>">Login</a>
            </div>
        </div>
        <p>&copy; <?= date('Y') ?> Student LMS. All rights reserved.</p>
    </footer>
</body>
</html>
