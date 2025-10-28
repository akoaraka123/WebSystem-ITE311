<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['material'])) {
        $file = $_FILES['material'];
        $uploadPath = __DIR__ . '/uploads/materials/';

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $target = $uploadPath . basename($file['name']);
        if (move_uploaded_file($file['tmp_name'], $target)) {
            echo "✅ Upload OK: " . htmlspecialchars($file['name']);
        } else {
            echo "❌ Upload failed: " . print_r($file['error'], true);
        }
    } else {
        echo "❌ No file uploaded.";
    }
}
?>

<!DOCTYPE html>
<html>
<body>
<h2>Test Upload</h2>
<form method="post" enctype="multipart/form-data">
  <input type="file" name="material" required>
  <button type="submit">Upload</button>
</form>
</body>
</html>
