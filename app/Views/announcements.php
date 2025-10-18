<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcements</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light py-4">

<div class="container">
    <!-- 🔐 Top Bar -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="text-info m-0">📢 Announcements</h4>
        <div>
            <span class="me-3 fw-semibold text-muted">
                Logged in as: <?= esc(session()->get('role')) ?>
            </span>
            <a href="<?= base_url('logout') ?>" class="btn btn-danger btn-sm">Logout</a>
        </div>
    </div>

    <!-- ⚠️ Flash Messages -->
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger text-center fw-semibold">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success text-center fw-semibold">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <hr>

    <!-- ✅ Announcements List -->
    <?php if (!empty($announcements)): ?>
        <?php foreach ($announcements as $a): ?>
            <div class="card mb-3 shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title text-primary mb-1"><?= esc($a['title']) ?></h5>
                    <p class="card-text"><?= esc($a['content']) ?></p>
                    <small class="text-muted">📅 Posted on: <?= esc($a['created_at']) ?></small>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="text-center text-muted mt-5">
            <h6>No announcements yet.</h6>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
