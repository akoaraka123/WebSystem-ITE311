<!DOCTYPE html>
<html>
<head>
    <title>Upload Material</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            background-color: #f4f7fb; 
            margin: 0; 
            padding: 0; 
        }
        .container { 
            max-width: 600px; 
            margin: 80px auto; 
            background: #fff; 
            padding: 25px; 
            border-radius: 10px; 
            box-shadow: 0 2px 10px #ccc; 
        }
        h2 { 
            color: #333; 
            margin-bottom: 20px; 
            text-align: center;
        }
        label { 
            display: block; 
            margin-bottom: 8px; 
            font-weight: bold; 
        }
        input[type="file"] { 
            width: 100%; 
            margin-bottom: 20px; 
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background: #fafafa;
        }
        .btn-submit {
            padding: 10px 15px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }
        .btn-submit:hover { 
            background: #0056b3; 
        }
        .btn-back {
            display: inline-block;
            text-align: center;
            padding: 8px 15px;
            background: #6c757d;
            color: white;
            border-radius: 4px;
            text-decoration: none;
            width: 100%;
            margin-top: 10px;
        }
        .btn-back:hover {
            background: #5a6268;
        }
        .flash {
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 15px;
            text-align: center;
            font-weight: bold;
        }
        .flash-success { 
            background: #d4edda; 
            color: #155724; 
            border: 1px solid #c3e6cb;
        }
        .flash-error { 
            background: #f8d7da; 
            color: #721c24; 
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>📁 Upload Course Material</h2>

    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="flash flash-success"><?= esc(session()->getFlashdata('success')) ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="flash flash-error"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <!-- Upload Form -->
    <form action="<?= base_url('materials/upload/' . $course_id) ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <label for="material">Select File:</label>
        <input type="file" 
               name="material" 
               id="material" 
               accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.zip,.rar,.png,.jpg,.jpeg,.gif,.txt" 
               required 
               onchange="checkFileSize(this)">
        <button type="submit" class="btn-submit">Upload</button>
        <a href="<?= base_url('dashboard') ?>" class="btn-back">⬅ Back to Dashboard</a>
    </form>

    <script>
        // ✅ Client-side file size check (50MB max)
        function checkFileSize(input) {
            const file = input.files[0];
            if (file && file.size > 50 * 1024 * 1024) { // 50 MB
                alert("❌ File is too big! Maximum size is 50MB.");
                input.value = ""; // Reset file input
            }
        }
    </script>
</div>

</body>
</html>
