<!DOCTYPE html>
<html>
<head>
    <title>Contact Page</title>
    <style>
        body { font-family: Arial; background-color: #fff8e1; margin:0; padding:0; }
        nav { background-color: #ff9800; padding: 10px; text-align: center; }
        nav a { color: white; margin: 0 15px; text-decoration: none; font-weight: bold; }
        .container { padding: 20px; text-align: center; }
        h1 { color: #555; }
    </style>
</head>
<body>
    <nav>
    <a href="<?= base_url('/') ?>">Home</a> | 
    <a href="<?= base_url('about') ?>">About</a> | 
    <a href="<?= base_url('contact') ?>">Contact</a>
    </nav>

    <div class="container">
        <h1>Contact Us</h1>
        <p>If you have questions, email us at <b>sample@email.com</b></p>
    </div>
</body>
</html>
