<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - Student LMS</title>
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
        
        .contact-content {
            background: white;
            padding: 60px 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .contact-grid {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 50px;
            align-items: start;
        }
        
        .contact-info {
            background: #f8f9fa;
            padding: 40px;
            border-radius: 15px;
        }
        
        .contact-info h3 {
            font-size: 24px;
            margin-bottom: 30px;
            color: #333;
        }
        
        .contact-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 25px;
        }
        
        .contact-icon {
            font-size: 24px;
            margin-right: 15px;
            color: #667eea;
            min-width: 30px;
        }
        
        .contact-details h4 {
            font-size: 18px;
            margin-bottom: 5px;
            color: #333;
        }
        
        .contact-details p {
            color: #666;
            line-height: 1.5;
        }
        
        .contact-form {
            background: #f8f9fa;
            padding: 40px;
            border-radius: 15px;
        }
        
        .contact-form h3 {
            font-size: 24px;
            margin-bottom: 30px;
            color: #333;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #333;
        }
        
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        
        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .form-group textarea {
            min-height: 120px;
            resize: vertical;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        .submit-btn {
            background: #667eea;
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }
        
        .faq {
            background: white;
            padding: 60px 20px;
        }
        
        .section-title {
            font-size: 36px;
            text-align: center;
            margin-bottom: 50px;
            color: #333;
        }
        
        .faq-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            max-width: 1000px;
            margin: 0 auto;
        }
        
        .faq-item {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 10px;
            border-left: 4px solid #667eea;
        }
        
        .faq-item h4 {
            font-size: 18px;
            margin-bottom: 10px;
            color: #333;
        }
        
        .faq-item p {
            color: #666;
            line-height: 1.5;
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
            
            .contact-grid {
                grid-template-columns: 1fr;
                gap: 30px;
            }
            
            .form-row {
                grid-template-columns: 1fr;
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
        <h1>Get in Touch</h1>
        <p>We're here to help you with any questions about Student LMS</p>
    </section>

    <!-- Contact Content -->
    <section class="contact-content">
        <div class="container">
            <div class="contact-grid">
                <!-- Contact Info -->
                <div class="contact-info">
                    <h3>How to Reach Us</h3>
                    
                    <div class="contact-item">
                        <div class="contact-icon">📍</div>
                        <div class="contact-details">
                            <h4>Visit Us</h4>
                            <p>123 Education Street<br>Manila, Philippines 1000</p>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-icon">📞</div>
                        <div class="contact-details">
                            <h4>Call Us</h4>
                            <p>+63 2 123 4567<br>Mon-Fri, 9AM-6PM</p>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-icon">📧</div>
                        <div class="contact-details">
                            <h4>Email Us</h4>
                            <p>support@studentlms.com<br>We'll respond within 24 hours</p>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-icon">💬</div>
                        <div class="contact-details">
                            <h4>Live Chat</h4>
                            <p>Chat with us online<br>Available 24/7 for students</p>
                        </div>
                    </div>
                </div>
                
                <!-- Contact Form -->
                <div class="contact-form">
                    <h3>Send Us a Message</h3>
                    <form action="#" method="POST">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="first-name">First Name</label>
                                <input type="text" id="first-name" name="first-name" required>
                            </div>
                            <div class="form-group">
                                <label for="last-name">Last Name</label>
                                <input type="text" id="last-name" name="last-name" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="subject">Subject</label>
                            <input type="text" id="subject" name="subject" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="message">Message</label>
                            <textarea id="message" name="message" required placeholder="Tell us how we can help you..."></textarea>
                        </div>
                        
                        <button type="submit" class="submit-btn">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq">
        <div class="container">
            <h2 class="section-title">Frequently Asked Questions</h2>
            <div class="faq-grid">
                <div class="faq-item">
                    <h4>How do I get started with Student LMS?</h4>
                    <p>Simply sign up for a free account, choose your courses, and start learning right away!</p>
                </div>
                
                <div class="faq-item">
                    <h4>Is Student LMS mobile-friendly?</h4>
                    <p>Yes! You can access Student LMS on any device - phone, tablet, or computer.</p>
                </div>
                
                <div class="faq-item">
                    <h4>Can I get help from teachers?</h4>
                    <p>Absolutely! Our teachers are here to help you succeed through messages and virtual office hours.</p>
                </div>
                
                <div class="faq-item">
                    <h4>How do I track my progress?</h4>
                    <p>Your dashboard shows your grades, completed lessons, and overall progress in real-time.</p>
                </div>
                
                <div class="faq-item">
                    <h4>Is there a mobile app?</h4>
                    <p>We're working on a mobile app! For now, just use our website on your phone's browser.</p>
                </div>
                
                <div class="faq-item">
                    <h4>How much does it cost?</h4>
                    <p>Student LMS is free for students! We believe education should be accessible to everyone.</p>
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
