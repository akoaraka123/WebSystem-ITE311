<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Upload Material</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .loading-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.7);
            z-index: 9999;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            color: white;
            font-size: 24px;
        }
    </style>
</head>
<body>

<!-- Loading Overlay -->
<div id="loadingOverlay" class="loading-overlay">
    <div class="spinner-border text-light" style="width: 80px; height: 80px;" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
    <div class="mt-3">UPLOADING... PLEASE WAIT</div>
</div>

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

          <!-- FORM -->
          <form id="uploadForm" action="<?= base_url('materials/upload/'.$course_id) ?>" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>
            
            <div class="mb-3">
              <label class="form-label fw-bold">1. Choose File (REQUIRED)</label>
              <input type="file" class="form-control" name="material" id="fileInput" accept=".pdf,.ppt,.pptx,.doc,.docx">
              <small class="text-muted">PDF, PPT, PPTX, DOC, DOCX only</small>
            </div>
            
            <div class="mb-3">
              <label class="form-label fw-bold">2. Select Term (REQUIRED)</label>
              <select class="form-control" name="term_id" id="termSelect">
                <option value="">-- Choose Term --</option>
                <?php if (!empty($terms)): ?>
                    <?php foreach ($terms as $term): ?>
                        <option value="<?= esc($term['id']) ?>"><?= esc(strtoupper($term['term_name'])) ?></option>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- Fallback if no terms from database -->
                    <option value="1">PRELIM</option>
                    <option value="2">MIDTERM</option>
                    <option value="3">FINAL</option>
                <?php endif; ?>
              </select>
            </div>
            
            <div id="errorMsg" class="alert alert-danger" style="display:none;"></div>
            
            <div class="d-grid gap-2">
              <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                UPLOAD NOW
              </button>
            </div>
          </form>
          
          <hr>
          <a href="<?= base_url('dashboard') ?>" class="btn btn-secondary">Back to Dashboard</a>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
// Immediate script - no waiting for DOMContentLoaded
var form = document.getElementById('uploadForm');
var fileInput = document.getElementById('fileInput');
var termSelect = document.getElementById('termSelect');
var submitBtn = document.getElementById('submitBtn');
var errorMsg = document.getElementById('errorMsg');
var loadingOverlay = document.getElementById('loadingOverlay');

if (form) {
    form.addEventListener('submit', function(e) {
        // Hide previous errors
        errorMsg.style.display = 'none';
        
        var errors = [];
        
        // Check file
        if (!fileInput || !fileInput.files || fileInput.files.length === 0) {
            errors.push('Please select a file to upload');
        } else {
            // Validate file type (client-side)
            var file = fileInput.files[0];
            var fileName = file.name.toLowerCase();
            var fileExt = fileName.split('.').pop();
            
            // Allowed types: PDF, PPT, PPTX, DOC, DOCX
            var allowedTypes = ['pdf', 'ppt', 'pptx', 'doc', 'docx'];
            
            // Blocked types: Images, Videos, Audio
            var imageTypes = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'svg', 'ico'];
            var videoTypes = ['mp4', 'avi', 'mov', 'wmv', 'flv', 'mkv', 'webm', 'm4v'];
            var audioTypes = ['mp3', 'wav', 'flac', 'aac', 'ogg', 'wma', 'm4a'];
            
            if (imageTypes.indexOf(fileExt) !== -1) {
                errors.push('❌ ERROR: Image files (photos) are not allowed. Only PPT, PDF, and DOCS files are permitted.');
            } else if (videoTypes.indexOf(fileExt) !== -1) {
                errors.push('❌ ERROR: Video files are not allowed. Only PPT, PDF, and DOCS files are permitted.');
            } else if (audioTypes.indexOf(fileExt) !== -1) {
                errors.push('❌ ERROR: Audio files (music) are not allowed. Only PPT, PDF, and DOCS files are permitted.');
            } else if (allowedTypes.indexOf(fileExt) === -1) {
                errors.push('❌ ERROR: Invalid file type. Only PPT (.ppt, .pptx), PDF (.pdf), and DOCS (.doc, .docx) files are allowed.');
            }
        }
        
        // Check term
        if (!termSelect || !termSelect.value || termSelect.value === '') {
            errors.push('Please select a term');
        }
        
        if (errors.length > 0) {
            e.preventDefault();
            errorMsg.innerHTML = '<strong>Cannot upload:</strong><br>' + errors.join('<br>');
            errorMsg.style.display = 'block';
            return false;
        }
        
        // Show loading overlay
        loadingOverlay.style.display = 'flex';
        submitBtn.disabled = true;
        submitBtn.innerHTML = 'UPLOADING...';
        
        // Don't prevent default - let the form submit
        return true;
    });
}
</script>
</body>
</html>
