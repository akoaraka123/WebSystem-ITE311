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
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .about-content {
            background: white;
            padding: 50px 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .section-title {
            font-size: 28px;
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }
        
        .mission-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 50px;
        }
        
        .mission-card {
            background: #f5f5f5;
            padding: 25px;
            border: 2px solid #999;
            border-radius: 3px;
            text-align: center;
        }
        
        .mission-icon {
            font-size: 40px;
            margin-bottom: 15px;
        }
        
        .mission-card h3 {
            font-size: 20px;
            margin-bottom: 12px;
            color: #333;
        }
        
        .mission-card p {
            color: #666;
            line-height: 1.5;
            font-size: 14px;
        }
        
        .stats {
            background: #1976d2;
            color: white;
            padding: 50px 20px;
            text-align: center;
            border-top: 3px solid #1565c0;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 25px;
            max-width: 1000px;
            margin: 0 auto;
        }
        
        .stat-item {
            padding: 20px;
        }
        
        .stat-number {
            font-size: 40px;
            font-weight: bold;
            margin-bottom: 8px;
        }
        
        .stat-label {
            font-size: 16px;
        }
        
        .team {
            background: white;
            padding: 50px 20px;
        }
        
        .team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            max-width: 900px;
            margin: 0 auto;
        }
        
        .team-member {
            text-align: center;
            padding: 25px;
            background: #f5f5f5;
            border: 2px solid #999;
            border-radius: 3px;
        }
        
        .member-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin: 0 auto 15px;
            background: #1976d2;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            color: white;
            border: 2px solid #1565c0;
        }
        
        .member-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }
        
        .member-role {
            color: #1976d2;
            margin-bottom: 12px;
            font-weight: bold;
        }
        
        .member-bio {
            color: #666;
            line-height: 1.5;
            font-size: 14px;
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
