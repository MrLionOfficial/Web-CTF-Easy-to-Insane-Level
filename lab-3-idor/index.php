<?php
// IDOR Lab - File Upload with Download Functionality
// Intentionally vulnerable to Insecure Direct Object Reference

// Create uploads directory if it doesn't exist
if (!is_dir('uploads')) {
    mkdir('uploads', 0755, true);
}

// Handle file upload
$message = '';
$uploadedFiles = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];
    $fileName = $file['name'];
    $fileTmpName = $file['tmp_name'];
    $fileSize = $file['size'];
    $fileError = $file['error'];
    
    if ($fileError === UPLOAD_ERR_OK) {
        // Generate unique ID for the file
        $fileId = rand(1, 100); // Random ID between 1-100
        
        // Ensure we have the flag at ID 55
        if ($fileId == 55) {
            $fileId = 56; // Move to next available ID
        }
        
        // Keep original filename for display but use ID for storage
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
        $newFileName = $fileId . '.' . $fileExtension;
        $filePath = 'uploads/' . $newFileName;
        
        if (move_uploaded_file($fileTmpName, $filePath)) {
            $message = "File uploaded successfully! File ID: " . $fileId;
        } else {
            $message = "Error uploading file.";
        }
    } else {
        $message = "Upload error: " . $fileError;
    }
}

// Get list of uploaded files
$uploadedFiles = [];
if (is_dir('uploads')) {
    $files = scandir('uploads');
    foreach ($files as $file) {
        if ($file != '.' && $file != '..' && $file != '55.txt') { // Hide flag file
            $fileId = pathinfo($file, PATHINFO_FILENAME);
            $fileExtension = pathinfo($file, PATHINFO_EXTENSION);
            $fileSize = filesize('uploads/' . $file);
            $uploadTime = date('Y-m-d H:i:s', filemtime('uploads/' . $file));
            
            // For display, we'll show a generic name since we don't store original names
            $displayName = 'file_' . $fileId . '.' . $fileExtension;
            
            $uploadedFiles[] = [
                'id' => $fileId,
                'name' => $displayName,
                'extension' => $fileExtension,
                'size' => $fileSize,
                'upload_time' => $uploadTime
            ];
        }
    }
}

// Create the flag file at ID 55
$flagContent = 'CTF{idor_master_flag}';
$flagPath = 'uploads/55.txt';
if (!file_exists($flagPath)) {
    file_put_contents($flagPath, $flagContent);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Manager</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }
        .upload-section {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }
        input[type="file"] {
            display: block;
            width: 100%;
            padding: 10px;
            border: 2px dashed #ddd;
            border-radius: 4px;
            background-color: #fff;
        }
        button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #0056b3;
        }
        .message {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
            text-align: center;
        }
        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .files-section h2 {
            color: #333;
            margin-bottom: 20px;
        }
        .files-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .files-table th,
        .files-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .files-table th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #333;
        }
        .files-table tr:hover {
            background-color: #f5f5f5;
        }
        .download-btn {
            background-color: #28a745;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            text-decoration: none;
            font-size: 12px;
        }
        .download-btn:hover {
            background-color: #218838;
        }
        .file-id {
            font-family: monospace;
            background-color: #e9ecef;
            padding: 2px 6px;
            border-radius: 3px;
        }
    </style>
</head>
<body>
    <div class="container">
            <h1>📁 File Manager</h1>
        
        <?php if ($message): ?>
            <div class="message <?php echo strpos($message, 'successfully') !== false ? 'success' : 'error'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <div class="upload-section">
            <h2>Upload New File</h2>
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="file">Choose File:</label>
                    <input type="file" name="file" id="file" required>
                </div>
                <button type="submit">Upload File</button>
            </form>
        </div>

        <div class="files-section">
            <h2>Uploaded Files</h2>
            <?php if (empty($uploadedFiles)): ?>
                <p>No files uploaded yet.</p>
            <?php else: ?>
                <table class="files-table">
                    <thead>
                        <tr>
                            <th>File ID</th>
                            <th>File Name</th>
                            <th>Size</th>
                            <th>Upload Time</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($uploadedFiles as $file): ?>
                            <tr>
                                <td><span class="file-id"><?php echo htmlspecialchars($file['id']); ?></span></td>
                                <td><?php echo htmlspecialchars($file['name']); ?></td>
                                <td><?php echo number_format($file['size']); ?> bytes</td>
                                <td><?php echo htmlspecialchars($file['upload_time']); ?></td>
                                <td>
                                    <a href="/download/<?php echo htmlspecialchars($file['id']); ?>" class="download-btn">Download</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
