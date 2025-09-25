<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        body { font-family: Arial; background-color: #f4f7fb; margin:0; padding:0; }
        nav { background-color: #4CAF50; padding: 10px; text-align: center; }
        nav a { color: white; margin: 0 15px; text-decoration: none; font-weight: bold; }
        .container { padding: 20px; max-width: 400px; margin: 80px auto; background: white; border-radius: 8px; box-shadow: 0 0 10px #ccc; }
        h2 { color: #333; text-align: center; }
        .form-group { margin-bottom: 15px; }
        label { display:block; margin-bottom:5px; }
        input { width: 100%; padding:8px; border-radius:5px; border:1px solid #ccc; }
        button { width: 100%; padding:10px; background:#4CAF50; color:white; border:none; border-radius:5px; cursor:pointer; }
        button:hover { background:#45a049; }
        .alert { padding:10px; margin-bottom:15px; border-radius:5px; }
        .alert-success { background:#d4edda; color:#155724; }
        .alert-danger  { background:#f8d7da; color:#721c24; }
        p.text-center { margin-top: 10px; }
    </style>
</head>
<body>
    <nav>
        <a href="<?= base_url('/') ?>">Home</a> | 
        <a href="<?= base_url('about') ?>">About</a> | 
        <a href="<?= base_url('contact') ?>">Contact</a>
    </nav>

    <div class="container">
        <h2>Login</h2>

        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>
        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <form method="post" action="<?= base_url('login') ?>">
            <?= csrf_field() ?>
            <div class="form-group">
                <label>Email or Username</label>
                <input type="text" name="login" value="<?= old('login') ?>" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit">Login</button>
        </form>

        <p class="text-center">
            No account? <a href="<?= base_url('register') ?>">Register here</a>
        </p>
    </div>
</body>
</html>
