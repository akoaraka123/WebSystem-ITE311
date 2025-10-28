<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Upload Material</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style> body { background-color:#f4f7fb; } </style>
    </head>
<body>
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
      <div class="card shadow-sm">
        <div class="card-body">
          <h4 class="card-title mb-3">Upload Material for Course ID: <?= esc($course_id) ?></h4>

          <!-- Flash Messages -->
          <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
          <?php endif; ?>
          <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
          <?php endif; ?>

          <!-- Upload Form -->
          <form action="<?= base_url('materials/upload/'.$course_id) ?>" method="post" enctype="multipart/form-data" class="mt-3">
            <?= csrf_field() ?>
            <div class="mb-3">
              <label for="material" class="form-label">Choose file</label>
              <input class="form-control" type="file" id="material" name="material" required>
              <div class="form-text">Allowed types: pdf, doc, docx, ppt, pptx, xls, xlsx, zip, rar, png, jpg, jpeg, gif, txt</div>
            </div>
            <div class="d-flex gap-2">
              <button type="submit" class="btn btn-primary">Upload Material</button>
              <a href="<?= base_url('dashboard') ?>" class="btn btn-secondary">Back to Dashboard</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
