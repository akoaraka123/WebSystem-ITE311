<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <style>
        body { font-family: Arial; background-color: #f4f7fb; margin:0; padding:0; }
        nav { background-color: #4CAF50; padding: 10px; text-align: center; }
        nav a { color: white; margin: 0 15px; text-decoration: none; font-weight: bold; }
        .container { padding: 20px; max-width: 500px; margin: 80px auto; background: white; border-radius: 8px; box-shadow: 0 0 10px #ccc; }
        h2 { color: #333; text-align: center; }
        .form-group { margin-bottom: 15px; }
        label { display:block; margin-bottom:5px; }
        input, select { width: 100%; padding:8px; border-radius:5px; border:1px solid #ccc; }
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
        <h2>Register</h2>

        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>
        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>
        <?php if(session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
                <?php foreach(session()->getFlashdata('errors') as $error): ?>
                    <p><?= esc($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="post" action="<?= base_url('register') ?>">
            <?= csrf_field() ?>
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" value="<?= old('name') ?>">
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="<?= old('email') ?>">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password">
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="password_confirm">
            </div>
            <div class="form-group">
                <label>Role</label>
                <select name="role">
                    <option value="student" <?= old('role')=='student'?'selected':'' ?>>Student</option>
                    <option value="instructor" <?= old('role')=='instructor'?'selected':'' ?>>Instructor</option>
                    <option value="admin" <?= old('role')=='admin'?'selected':'' ?>>Admin</option>
                </select>
            </div>
            <button type="submit">Register</button>
        </form>
        <p class="text-center">Already have an account? <a href="<?= base_url('login') ?>">Login</a></p>
    </div>
</body>
</html>
