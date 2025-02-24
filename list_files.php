<?php
$filesDir = '/server/files/'; // Directory where files are stored
$webFilesDir = '/files/'; // URL path that Apache will serve

if (!is_dir($filesDir)) {
    die("The 'files' directory does not exist.");
}

// Get all files in the directory
$files = scandir($filesDir);
$files = array_diff($files, array('.', '..'));

if (empty($files)) {
    echo "<p>No files found in the directory.</p>";
} else {
    echo "<ul>";
    foreach ($files as $file) {
        $filePath = $webFilesDir . $file; // Correct file path for Apache to serve
        if (is_file($filesDir . $file)) {
            echo "<li><a href='$filePath' download>$file</a></li>";
        }
    }
    echo "</ul>";
}
