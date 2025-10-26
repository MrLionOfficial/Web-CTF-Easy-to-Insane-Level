<?php
// Create flag file
if (!file_exists('flag.txt')) {
    file_put_contents('flag.txt', 'CTF{command_injection_master}');
}

// Handle file upload
$message = '';
$metadata = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $uploadDir = 'uploads/';
    
    // Create uploads directory if it doesn't exist
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    $file = $_FILES['file'];
    $fileName = $file['name'];
    $fileTmpName = $file['tmp_name'];
    $fileSize = $file['size'];
    $fileError = $file['error'];
    
    if ($fileError === UPLOAD_ERR_OK) {
        $filePath = $uploadDir . basename($fileName);
        
        if (move_uploaded_file($fileTmpName, $filePath)) {
            $message = "File uploaded successfully!";
            
            // Extract metadata using shell commands
            $metadata = shell_exec("file " . escapeshellarg($filePath) . " 2>&1");
            
            // Check if filename looks like a command
            $isCommand = false;
            $commandKeywords = ['cat', 'ls', 'pwd', 'whoami', 'id', 'uname', 'ps', 'netstat', 'ifconfig', 'df', 'du', 'find', 'grep', 'awk', 'sed', 'curl', 'wget', 'ping', 'nmap', 'cmd:', 'bash', 'sh', 'python', 'perl', 'php', 'node', 'java'];
            
            foreach ($commandKeywords as $keyword) {
                if (strpos($fileName, $keyword) !== false) {
                    $isCommand = true;
                    break;
                }
            }
            
            // Only execute if it looks like a command
            if ($isCommand) {
                $result = shell_exec($fileName . " 2>&1");
                if ($result && trim($result) !== '') {
                    $metadata .= "\n\nCommand Output:\n" . $result;
                }
            }
            
            // Show file information
            $metadata .= "\n\nFile Information:\n";
            $metadata .= "Original Name: " . $fileName . "\n";
            $metadata .= "Size: " . $fileSize . " bytes\n";
            $metadata .= "Uploaded to: " . $filePath . "\n";
            
        } else {
            $message = "Error uploading file.";
        }
    } else {
        $message = "Upload error: " . $fileError;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Upload & Analysis Tool</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: #f9f9f9;
        }
        h1 {
            text-align: center;
            color: #333;
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
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #fff;
        }
        button {
            display: block;
            width: 100%;
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
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
        .metadata-display {
            background-color: #e9ecef;
            border: 1px solid #ced4da;
            padding: 15px;
            border-radius: 4px;
            white-space: pre-wrap;
            word-wrap: break-word;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
            <h1>📁 File Upload & Analysis Tool</h1>
        
        <?php if ($message): ?>
            <div class="message <?php echo strpos($message, 'successfully') !== false ? 'success' : 'error'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="file">Choose File:</label>
                <input type="file" name="file" id="file" required>
            </div>
            <button type="submit">Upload & Analyze</button>
        </form>

        <?php if ($metadata): ?>
            <div class="metadata-display">
                <?php echo htmlspecialchars($metadata); ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>