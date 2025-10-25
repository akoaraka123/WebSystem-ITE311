<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['material'])) {
        $file = $_FILES['material'];
        echo "Error code: " . $file['error'] . "<br>";
        echo "Original name: " . $file['name'] . "<br>";
        echo "Temp location: " . $file['tmp_name'] . "<br>";
    } else {
        echo "No file detected!";
    }
}
?>

<form method="post" enctype="multipart/form-data">
    <input type="file" name="material">
    <button type="submit">Upload</button>
</form>
