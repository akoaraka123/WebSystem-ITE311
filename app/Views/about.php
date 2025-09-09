<!DOCTYPE html>
<html>
<head>
    <title>About Page</title>
    <style>
        body { 
            font-family: Arial; 
            background-color: #eef6ff; 
            margin: 0; 
            padding: 0; 
        }
        nav { 
            background-color: #2196F3; 
            padding: 10px; 
            text-align: center; 
        }
        nav a { 
            color: white; 
            margin: 0 15px; 
            text-decoration: none; 
            font-weight: bold; 
        }
        nav a:hover {
            text-decoration: underline;
        }
        .container { 
            padding: 20px; 
            text-align: center; 
        }
        h1 { color: #444; }

        /* Right-side login/register buttons */
        .side-buttons {
            position: fixed;
            top: 100px;
            right: 20px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .side-buttons a {
            display: block;
            padding: 10px 15px;
            background-color: #2196F3;
            color: white;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
            transition: background-color 0.2s;
        }
        .side-buttons a:hover {
            background-color: #1976D2;
        }
    </style>
</head>
<body>
    <nav>
        <a href="<?= base_url('/') ?>">Home</a> | 
        <a href="<?= base_url('about') ?>">About</a> | 
        <a href="<?= base_url('contact') ?>">Contact</a>
    </nav>

    <!-- Right-side login/register buttons -->
    <div class="side-buttons">
        <a href="<?= base_url('/login') ?>">Login</a>
        <a href="<?= base_url('/register') ?>">Register</a>
    </div>

    <div class="container">
        <h1>About Us</h1>
        <p>This page tells about our LMS project. It is created for our ITE311 Laboratory.</p>
    </div>
</body>
</html>
