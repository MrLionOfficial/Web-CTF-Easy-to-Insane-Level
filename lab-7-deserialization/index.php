<?php
// Lab 7: Deserialization Challenge - Insane Level
// PHP Object Injection vulnerability

// Create flag file
if (!file_exists('flag.txt')) {
    file_put_contents('flag.txt', 'CTF{deserialization_master_insane}');
}

// Vulnerable class
class User {
    public $username;
    public $role;
    public $isAdmin;
    
    public function __construct($username = '', $role = 'user', $isAdmin = false) {
        $this->username = $username;
        $this->role = $role;
        $this->isAdmin = $isAdmin;
    }
    
    public function __destruct() {
        // This method is called when object is destroyed
        if ($this->isAdmin) {
            echo "<div style='background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
            echo "<h3>🔓 Admin Access Granted!</h3>";
            echo "<p>Welcome, " . htmlspecialchars($this->username) . "!</p>";
            echo "<p><strong>Flag:</strong> " . file_get_contents('flag.txt') . "</p>";
            echo "</div>";
        }
    }
}

// Another vulnerable class
class Config {
    public $settings;
    
    public function __construct($settings = []) {
        $this->settings = $settings;
    }
    
    public function __wakeup() {
        // This method is called when object is unserialized
        if (isset($this->settings['admin']) && $this->settings['admin'] === true) {
            echo "<div style='background: #d1ecf1; color: #0c5460; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
            echo "<h3>⚙️ Configuration Loaded</h3>";
            echo "<p>Admin settings activated!</p>";
            echo "<p><strong>Flag:</strong> " . file_get_contents('flag.txt') . "</p>";
            echo "</div>";
        }
    }
}

$message = '';
$error = '';

// Handle serialized data
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['data'])) {
    $data = $_POST['data'];
    
    // Basic validation
    if (empty($data)) {
        $error = "Please provide serialized data.";
    } else {
        // Attempt to unserialize (VULNERABLE!)
        $unserialized = @unserialize($data);
        
        if ($unserialized === false) {
            $error = "Invalid serialized data format.";
        } else {
            $message = "Data processed successfully!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Processor</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
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
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            resize: vertical;
            font-family: monospace;
        }
        button {
            background: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background: #0056b3;
        }
        .message {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .message.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .message.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
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
        .example {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .example h4 {
            margin-top: 0;
            color: #495057;
        }
        code {
            background: #e9ecef;
            padding: 2px 4px;
            border-radius: 3px;
            font-family: monospace;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 Data Processor</h1>
        
        <div class="info">
            <h3>📋 Service Information</h3>
            <p>This service processes serialized PHP data. Submit your serialized data for processing.</p>
        </div>
        
        <div class="example">
            <h4>Example Serialized Data:</h4>
            <code>O:4:"User":3:{s:8:"username";s:5:"admin";s:4:"role";s:5:"admin";s:7:"isAdmin";b:1;}</code>
        </div>
        
        <?php if ($message): ?>
            <div class="message success">
                <strong>Success:</strong> <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="message error">
                <strong>Error:</strong> <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="data">Serialized Data:</label>
                <textarea name="data" id="data" rows="6" placeholder="Enter serialized PHP data..." required></textarea>
            </div>
            <button type="submit">Process Data</button>
        </form>
    </div>
</body>
</html>
