<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About - Student LMS</title>
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
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .about-content {
            background: white;
            padding: 60px 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .section-title {
            font-size: 36px;
            text-align: center;
            margin-bottom: 40px;
            color: #333;
        }
        
        .mission-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 60px;
        }
        
        .mission-card {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            transition: transform 0.3s;
        }
        
        .mission-card:hover {
            transform: translateY(-5px);
        }
        
        .mission-icon {
            font-size: 48px;
            margin-bottom: 20px;
        }
        
        .mission-card h3 {
            font-size: 24px;
            margin-bottom: 15px;
            color: #333;
        }
        
        .mission-card p {
            color: #666;
            line-height: 1.6;
        }
        
        .stats {
            background: #667eea;
            color: white;
            padding: 60px 20px;
            text-align: center;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 30px;
            max-width: 1000px;
            margin: 0 auto;
        }
        
        .stat-item {
            padding: 20px;
        }
        
        .stat-number {
            font-size: 48px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .stat-label {
            font-size: 18px;
            opacity: 0.9;
        }
        
        .team {
            background: white;
            padding: 60px 20px;
        }
        
        .team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            max-width: 900px;
            margin: 0 auto;
        }
        
        .team-member {
            text-align: center;
            padding: 30px;
            background: #f8f9fa;
            border-radius: 15px;
            transition: transform 0.3s;
        }
        
        .team-member:hover {
            transform: translateY(-5px);
        }
        
        .member-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin: 0 auto 20px;
            background: #667eea;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 36px;
            color: white;
        }
        
        .member-name {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }
        
        .member-role {
            color: #667eea;
            margin-bottom: 15px;
        }
        
        .member-bio {
            color: #666;
            line-height: 1.6;
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
        <h1>About Student LMS</h1>
        <p>We're here to make learning online fun, easy, and accessible for every student</p>
    </section>

    <!-- About Content -->
    <section class="about-content">
        <div class="container">
            <h2 class="section-title">Our Mission</h2>
            <div class="mission-grid">
                <div class="mission-card">
                    <div class="mission-icon">🎯</div>
                    <h3>Simple Learning</h3>
                    <p>We believe learning should be easy and enjoyable. Our platform is designed to be simple so you can focus on what matters - your education.</p>
                </div>
                <div class="mission-card">
                    <div class="mission-icon">🤝</div>
                    <h3>Student Support</h3>
                    <p>We're here to help you succeed. Connect with teachers and classmates, get help when you need it, and learn together.</p>
                </div>
                <div class="mission-card">
                    <div class="mission-icon">🚀</div>
                    <h3>Always Improving</h3>
                    <p>We're always working to make our platform better for students. Your feedback helps us create the best learning experience.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats">
        <div class="container">
            <h2 class="section-title" style="color: white;">By the Numbers</h2>
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number">1,000+</div>
                    <div class="stat-label">Happy Students</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">50+</div>
                    <div class="stat-label">Amazing Teachers</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">100+</div>
                    <div class="stat-label">Courses Available</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">95%</div>
                    <div class="stat-label">Success Rate</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="team">
        <div class="container">
            <h2 class="section-title">Meet Our Team</h2>
            <div class="team-grid">
                <div class="team-member">
                    <div class="member-avatar">👨‍🏫</div>
                    <div class="member-name">Alex Chen</div>
                    <div class="member-role">Founder & CEO</div>
                    <div class="member-bio">Passionate about making education accessible and fun for everyone.</div>
                </div>
                <div class="team-member">
                    <div class="member-avatar">👩‍🏫</div>
                    <div class="member-name">Sarah Johnson</div>
                    <div class="member-role">Head of Learning</div>
                    <div class="member-bio">Expert in creating engaging learning experiences for students.</div>
                </div>
                <div class="team-member">
                    <div class="member-avatar">👨‍💻</div>
                    <div class="member-name">Mike Davis</div>
                    <div class="member-role">Tech Lead</div>
                    <div class="member-bio">Building the best learning platform with modern technology.</div>
                </div>
            </div>
        </div>
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
