<?php
// Lab 6: SSRF Challenge - Insane Level
// Multiple layers of URL validation and filtering

// Create flag file
if (!file_exists('flag.txt')) {
    file_put_contents('flag.txt', 'CTF{ssrf_master_insane}');
}

$result = '';
$error = '';

// Handle URL fetch request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['url'])) {
    $url = $_POST['url'];
    
    // Multiple layers of validation (intentionally bypassable)
    $parsed = parse_url($url);
    
    // Block common internal IPs
    $blockedHosts = ['127.0.0.1', 'localhost', '0.0.0.0', '::1'];
    $blockedPorts = [22, 23, 25, 53, 80, 135, 139, 445, 993, 995];
    
    if (isset($parsed['host'])) {
        $host = $parsed['host'];
        
        // Check for blocked hosts
        if (in_array($host, $blockedHosts)) {
            $error = "Access to internal hosts is not allowed.";
        }
        
        // Check for blocked ports
        if (isset($parsed['port']) && in_array($parsed['port'], $blockedPorts)) {
            $error = "Access to restricted ports is not allowed.";
        }
        
        // Block file:// protocol
        if (strpos($url, 'file://') === 0) {
            $error = "File protocol is not allowed.";
        }
        
        // Block gopher:// protocol
        if (strpos($url, 'gopher://') === 0) {
            $error = "Gopher protocol is not allowed.";
        }
        
        // Only allow http and https
        if (!preg_match('/^https?:\/\//', $url)) {
            $error = "Only HTTP and HTTPS protocols are allowed.";
        }
        
        // Block URLs containing certain patterns
        if (preg_match('/[0-9]+\.[0-9]+\.[0-9]+\.[0-9]+/', $url)) {
            $error = "Direct IP addresses are not allowed.";
        }
        
        if (!$error) {
            // Attempt to fetch the URL
            $context = stream_context_create([
                'http' => [
                    'timeout' => 5,
                    'user_agent' => 'Mozilla/5.0 (compatible; URLFetcher/1.0)',
                    'follow_location' => false
                ]
            ]);
            
            $content = @file_get_contents($url, false, $context);
            
            if ($content !== false) {
                $result = htmlspecialchars(substr($content, 0, 1000));
                if (strlen($content) > 1000) {
                    $result .= "\n\n... (truncated)";
                }
            } else {
                $error = "Failed to fetch the URL. Please check if the URL is valid and accessible.";
            }
        }
    } else {
        $error = "Invalid URL format.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>URL Fetcher</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="url"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        button {
            background: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background: #0056b3;
        }
        .message {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .message.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .result {
            background: #d1ecf1;
            border: 1px solid #bee5eb;
            padding: 15px;
            border-radius: 5px;
            white-space: pre-wrap;
            font-family: monospace;
            max-height: 400px;
            overflow-y: auto;
        }
        .info {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .info h3 {
            margin-top: 0;
            color: #856404;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🌐 URL Fetcher</h1>
        
        <div class="info">
            <h3>📋 Service Information</h3>
            <p>This service allows you to fetch content from external URLs. Please note the following restrictions:</p>
            <ul>
                <li>Only HTTP and HTTPS protocols are allowed</li>
                <li>Internal hosts (localhost, 127.0.0.1) are blocked</li>
                <li>Direct IP addresses are not allowed</li>
                <li>Certain ports are restricted</li>
                <li>File and Gopher protocols are blocked</li>
            </ul>
        </div>
        
        <?php if ($error): ?>
            <div class="message error">
                <strong>Error:</strong> <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="url">Enter URL to fetch:</label>
                <input type="url" name="url" id="url" placeholder="https://example.com" required>
            </div>
            <button type="submit">Fetch URL</button>
        </form>
        
        <?php if ($result): ?>
            <h2>Fetch Result:</h2>
            <div class="result"><?php echo $result; ?></div>
        <?php endif; ?>
    </div>
</body>
</html>
