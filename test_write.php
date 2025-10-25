<?php
$file = 'uploads/materials/test.txt';

if(file_put_contents($file, 'Hello, world!') !== false){
    echo "✅ Write OK! The folder is writable.";
} else {
    echo "❌ Cannot write! Check folder permissions.";
}
