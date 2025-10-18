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

        /* Right-side login/register buttons */
        .side-buttons {
            position: fixed;
            top: 100px;
            right: 20px; /* dito nag-change mula left */
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .side-buttons a {
            display: block;
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px; 
            transition: background-color 0.2s;
        }
        .side-buttons a:hover {
            background-color: #45a049;
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
        <h1>Welcome to My LMS Project</h1>
        <p>This is the homepage. Here we can put some introduction about the system.</p>
    </div>
</body>
</html>
