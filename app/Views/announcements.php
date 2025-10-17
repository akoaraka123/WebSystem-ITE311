<!DOCTYPE html>
<html>
<head>
    <title>Announcements</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-4">
    <div class="container">
        <h2 class="mb-4 text-center text-info">Announcements</h2>
        <?php if (!empty($announcements)): ?>
            <?php foreach ($announcements as $a): ?>
                <div class="card mb-3 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title"><?= esc($a['title']) ?></h5>
                        <p class="card-text"><?= esc($a['content']) ?></p>
                        <small class="text-muted">Posted on: <?= esc($a['date']) ?></small>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center">No announcements yet.</p>
        <?php endif; ?>
    </div>
</body>
</html>
