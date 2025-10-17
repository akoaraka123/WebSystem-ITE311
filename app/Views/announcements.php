<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Announcements</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4 text-center">📢 Announcements</h2>

    <?php if (!empty($announcements)): ?>
        <?php foreach ($announcements as $a): ?>
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title"><?= esc($a['title']) ?></h5>
                    <p class="card-text"><?= esc($a['content']) ?></p>
                    <small class="text-muted">Posted on: <?= esc($a['created_at']) ?></small>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="alert alert-info text-center">
            No announcements yet.
        </div>
    <?php endif; ?>
</div>
</body>
</html>
