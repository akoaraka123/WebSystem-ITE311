<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; background:#f4f7fb; margin:0; padding:0; }
        nav { background:#333; color:#fff; padding:10px; }
        nav span { float:right; }
        .container { max-width:900px; margin:30px auto; padding:20px; background:#fff; border-radius:8px; box-shadow:0 0 10px #ccc; }
    </style>
</head>
<body>
    <nav>
        <strong>Admin Panel</strong>
        <span><?= esc($user['name']) ?> (<?= esc($user['role']) ?>) |
            <a href="<?= base_url('logout') ?>" style="color:#fff;">Logout</a></span>
    </nav>
    <div class="container">
        <h2>Welcome, Admin!</h2>
        <p>Email: <?= esc($user['email']) ?></p>
        <p>Use this panel to manage users, books, and system settings.</p>
    </div>
</body>
</html>
