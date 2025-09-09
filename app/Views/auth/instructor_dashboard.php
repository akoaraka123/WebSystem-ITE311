<!DOCTYPE html>
<html>
<head>
    <title>Instructor Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; background:#e3f2fd; margin:0; padding:0; }
        nav { background:#1976d2; color:#fff; padding:10px; }
        nav span { float:right; }
        .container { max-width:900px; margin:30px auto; padding:20px; background:#fff; border-radius:8px; box-shadow:0 0 10px #ccc; }
    </style>
</head>
<body>
    <nav>
        <strong>Instructor Panel</strong>
        <span><?= esc($user['name']) ?> (<?= esc($user['role']) ?>) |
            <a href="<?= base_url('logout') ?>" style="color:#fff;">Logout</a></span>
    </nav>
    <div class="container">
        <h2>Welcome, Instructor!</h2>
        <p>Email: <?= esc($user['email']) ?></p>
        <p>Here you can manage courses, monitor students, and upload materials.</p>
    </div>
</body>
</html>
