<!DOCTYPE html>
<html>
<head>
    <title>About Page</title>
    <style>
        body { font-family: Arial; background-color: #eef6ff; margin:0; padding:0; }
        nav { background-color: #2196F3; padding: 10px; text-align: center; }
        nav a { color: white; margin: 0 15px; text-decoration: none; font-weight: bold; }
        .container { padding: 20px; text-align: center; }
        h1 { color: #444; }
    </style>
</head>
<body>
    <nav>
    <a href="<?= base_url('/') ?>">Home</a> | 
    <a href="<?= base_url('about') ?>">About</a> | 
    <a href="<?= base_url('contact') ?>">Contact</a>
    </nav>


    <div class="container">
        <h1>About Us</h1>
        <p>This page tells about our LMS project. It is created for our ITE311 Laboratory.</p>
    </div>
</body>
</html>
