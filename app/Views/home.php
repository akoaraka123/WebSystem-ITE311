<!DOCTYPE html>
<html>
<head>
    <title>Homepage</title>
    <style>
        body {
            font-family: Arial;
            background-color: #f4f7fb;
            margin: 0;
            padding: 0;
        }
        nav {
            background-color: #4CAF50;
            padding: 10px;
            text-align: center;
        }
        nav a {
            color: white;
            margin: 0 15px;
            text-decoration: none;
            font-weight: bold;
        }
        .container {
            padding: 20px;
            text-align: center;
        }
        h1 { color: #333; }
    </style>
</head>
<body>
    <nav>
    <a href="<?= base_url('/') ?>">Home</a> | 
    <a href="<?= base_url('about') ?>">About</a> | 
    <a href="<?= base_url('contact') ?>">Contact</a>
    </nav>


    <div class="container">
        <h1>Welcome to My LMS Project</h1>
        <p>This is the homepage. Here we can put some introduction about the system.</p>
    </div>
</body>
</html>
