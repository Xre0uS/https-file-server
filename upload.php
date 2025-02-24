<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $uploadDir = '/server/uploads/'; // Directory to save files

    // Handle file uploads from the HTML form or curl -F
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        // Single file upload (curl -F 'file=@a')
        $filename = basename($_FILES['file']['name']);
        $targetFile = $uploadDir . $filename;

        // Attempt to move the uploaded file
        if (move_uploaded_file($_FILES['file']['tmp_name'], $targetFile)) {
            echo "Uploaded: " . htmlspecialchars($filename) . "<br>";
        } else {
            echo "Error uploading: " . htmlspecialchars($filename) . " - Could not move file.<br>";
        }
    } elseif (isset($_FILES['files']) && !empty($_FILES['files']['name'][0])) {
        // Multiple file uploads (HTML form)
        foreach ($_FILES['files']['tmp_name'] as $key => $tmpFile) {
            $filename = basename($_FILES['files']['name'][$key]);
            $targetFile = $uploadDir . $filename;

            // Check if the file was uploaded successfully
            if ($_FILES['files']['error'][$key] === UPLOAD_ERR_OK) {
                // Attempt to move the uploaded file
                if (move_uploaded_file($tmpFile, $targetFile)) {
                    echo "Uploaded: " . htmlspecialchars($filename) . "<br>";
                } else {
                    echo "Error uploading: " . htmlspecialchars($filename) . " - Could not move file.<br>";
                }
            } else {
                echo "Error uploading: " . htmlspecialchars($filename) . " - Error code: " . $_FILES['files']['error'][$key] . "<br>";
            }
        }
    }

    // Handle raw POST data from curl -d
    else {
        $rawData = file_get_contents("php://input"); // Read raw POST data
        if (!empty($rawData)) {
            $filename = $uploadDir . 'raw_post_data_' . time() . '.txt'; // Unique filename
            if (file_put_contents($filename, $rawData)) {
                echo "Raw POST data saved to: " . htmlspecialchars($filename) . "<br>";
            } else {
                echo "Error saving raw POST data.<br>";
            }
        } else {
            echo "No raw POST data received.<br>";
        }
    }
} else {
    echo "Invalid request method. Only POST requests are allowed.<br>";
}
