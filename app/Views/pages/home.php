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
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
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
        
        .hero {
            text-align: center;
            padding: 80px 20px;
            color: white;
        }
        
        .hero h1 {
            font-size: 48px;
            margin-bottom: 20px;
            animation: fadeInUp 1s ease;
        }
        
        .hero p {
            font-size: 20px;
            margin-bottom: 30px;
            opacity: 0.9;
            animation: fadeInUp 1s ease 0.2s;
        }
        
        .btn-group {
            display: flex;
            gap: 20px;
            justify-content: center;
            animation: fadeInUp 1s ease 0.4s;
        }
        
        .btn {
            padding: 15px 30px;
            border: none;
            border-radius: 25px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.3s, box-shadow 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-primary {
            background: white;
            color: #667eea;
        }
        
        .btn-secondary {
            background: transparent;
            color: white;
            border: 2px solid white;
        }
        
        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }
        
        .features {
            background: white;
            padding: 60px 20px;
            text-align: center;
        }
        
        .features h2 {
            font-size: 36px;
            margin-bottom: 40px;
            color: #333;
        }
        
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .feature-card {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 15px;
            transition: transform 0.3s;
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
        }
        
        .feature-icon {
            font-size: 48px;
            margin-bottom: 20px;
        }
        
        .feature-card h3 {
            font-size: 20px;
            margin-bottom: 15px;
            color: #333;
        }
        
        .feature-card p {
            color: #666;
            line-height: 1.6;
        }
        
        .cta {
            background: #667eea;
            color: white;
            padding: 60px 20px;
            text-align: center;
        }
        
        .cta h2 {
            font-size: 32px;
            margin-bottom: 20px;
        }
        
        .cta p {
            font-size: 18px;
            margin-bottom: 30px;
            opacity: 0.9;
        }
        
        .footer {
            background: #333;
            color: white;
            padding: 40px 20px;
            text-align: center;
        }
        
        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
            margin-bottom: 30px;
        }
        
        .footer-section h4 {
            margin-bottom: 15px;
            color: #667eea;
        }
        
        .footer-section a {
            color: #ccc;
            text-decoration: none;
            display: block;
            margin-bottom: 8px;
            transition: color 0.3s;
        }
        
        .footer-section a:hover {
            color: white;
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
            .hero h1 {
                font-size: 32px;
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
        <div class="logo">📚 Student LMS</div>
        <div class="nav-links">
            <a href="<?= base_url() ?>">Home</a>
            <a href="<?= base_url('about') ?>">About</a>
            <a href="<?= base_url('contact') ?>">Contact</a>
            <a href="<?= base_url('login') ?>">Login</a>
            <a href="<?= base_url('register') ?>">Register</a>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <h1>Welcome to Student LMS</h1>
        <p>Your gateway to amazing online learning experiences</p>
        <div class="btn-group">
            <a href="<?= base_url('register') ?>" class="btn btn-primary">Get Started</a>
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
        <p>Join thousands of students already using our platform</p>
        <a href="<?= base_url('register') ?>" class="btn btn-primary">Sign Up Now</a>
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
                <a href="<?= base_url('register') ?>">Register</a>
            </div>
        </div>
        <p>&copy; <?= date('Y') ?> Student LMS. All rights reserved.</p>
    </footer>
</body>
</html>
