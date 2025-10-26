<?php
// IDOR Vulnerability - Download API Endpoint
// Intentionally vulnerable to Insecure Direct Object Reference

// Get the file ID from the URL
$fileId = isset($_GET['id']) ? $_GET['id'] : '';

// VULNERABILITY: No authorization check - anyone can download any file by ID
if (!empty($fileId)) {
    // Check if file exists
    $filePath = 'uploads/' . $fileId . '.txt';
    
    // If the specific file doesn't exist, try with other extensions
    if (!file_exists($filePath)) {
        $extensions = ['txt', 'pdf', 'jpg', 'png', 'doc', 'docx'];
        foreach ($extensions as $ext) {
            $testPath = 'uploads/' . $fileId . '.' . $ext;
            if (file_exists($testPath)) {
                $filePath = $testPath;
                break;
            }
        }
    }
    
    if (file_exists($filePath)) {
        // Special handling for flag file (ID 55)
        if ($fileId == '55') {
            // Return flag content directly
            $flagContent = file_get_contents($filePath);
            header('Content-Type: text/plain');
            header('Content-Disposition: inline; filename="flag.txt"');
            echo $flagContent;
            exit;
        } else {
            // Regular file download
            $fileName = basename($filePath);
            $fileSize = filesize($filePath);
            $fileContent = file_get_contents($filePath);
            
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $fileName . '"');
            header('Content-Length: ' . $fileSize);
            echo $fileContent;
            exit;
        }
    } else {
        // File not found
        http_response_code(404);
        echo "File not found";
        exit;
    }
} else {
    // No file ID provided
    http_response_code(400);
    echo "File ID required";
    exit;
}
?>
