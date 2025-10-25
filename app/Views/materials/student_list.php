<!-- app/Views/materials/student_list.php -->
<!doctype html>
<html>
<head>
    <title>Course Materials</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<div class="container">
    <h4>Materials for: <?= esc($course_title ?? 'Course') ?></h4>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <?php if (!empty($materials)): ?>
        <table class="table table-striped">
            <thead><tr><th>File</th><th>Uploaded</th><th></th></tr></thead>
            <tbody>
            <?php foreach($materials as $m): ?>
                <tr>
                    <td><?= esc($m['file_name']) ?></td>
                    <td><?= esc($m['created_at']) ?></td>
                    <td>
                        <a href="<?= site_url('/materials/download/'.$m['id']) ?>" class="btn btn-sm btn-primary">Download</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="text-muted">Walang materials sa course na ito.</p>
    <?php endif; ?>
</div>
</body>
</html>
