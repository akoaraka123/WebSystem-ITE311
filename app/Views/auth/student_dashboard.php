<!DOCTYPE html>
<html>
<head>
    <title>Student Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; background:#fff8e1; margin:0; padding:0; }
        nav { background:#ff9800; color:#fff; padding:10px; }
        nav span { float:right; }
        .container { max-width:900px; margin:30px auto; padding:20px; background:#fff; border-radius:8px; box-shadow:0 0 10px #ccc; }
    </style>
</head>
<body>
    <nav>
        <strong>Student Panel</strong>
        <span><?= esc($user['name']) ?> (<?= esc($user['role']) ?>) |
            <a href="<?= base_url('logout') ?>" style="color:#fff;">Logout</a></span>
    </nav>
    <div class="container">
        <h2>Welcome, Student!</h2>
        <p>Email: <?= esc($user['email']) ?></p>
        <p>You can browse books, borrow items, and view your profile here.</p>
    </div>
</body>
</html>
