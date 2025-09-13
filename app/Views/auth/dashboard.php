<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f7fb; margin:0; padding:0; }
        nav { background-color: #4CAF50; padding: 12px; text-align: center; }
        nav a { color: white; margin: 0 15px; text-decoration: none; font-weight: bold; }
        .container { max-width: 700px; margin: 60px auto; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px #ccc; }
        h2 { color: #333; }
        p { margin: 8px 0; }
        .flash { padding: 10px; border-radius: 5px; margin-bottom: 15px; }
        .flash-success { background: #d4edda; color: #155724; }
    </style>
</head>
<body>

    <nav>
        <a href="<?= base_url('dashboard') ?>">Dashboard</a>
        <a href="<?= base_url('logout') ?>">Logout</a>
    </nav>

    <div class="container">
        <h2>Welcome, <?= esc($user['name']) ?> 🎉</h2>

        <?php if (!empty($flash)): ?>
            <div class="flash flash-success"><?= esc($flash) ?></div>
        <?php endif; ?>

        <p><strong>User ID:</strong> <?= esc($user['id']) ?></p>
        <p><strong>Email:</strong> <?= esc($user['email']) ?></p>
        <p><strong>Role:</strong> <?= esc($user['role']) ?></p>

        <p style="margin-top:20px;">You are now logged in. ✅</p>
    </div>

</body>
</html>
