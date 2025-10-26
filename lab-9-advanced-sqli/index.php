<?php
// Lab 9: Advanced SQLi Challenge - Insane Level
// Multiple layers of SQL injection protection with bypasses

session_start();

// Create flag file
if (!file_exists('flag.txt')) {
    file_put_contents('flag.txt', 'CTF{advanced_sqli_master_insane}');
}

// Database connection details
$servername = "db";
$username = "root";
$password = "password";
$dbname = "advanced_sqli_lab";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = '';
$success = '';

// Handle search request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search'])) {
    $search = $_POST['search'];
    
    // Multiple layers of filtering (intentionally bypassable)
    $search = trim($search);
    $search = stripslashes($search);
    $search = htmlspecialchars($search);
    
    // Block common SQL keywords
    $blockedKeywords = ['union', 'select', 'insert', 'update', 'delete', 'drop', 'create', 'alter'];
    $searchLower = strtolower($search);
    
    foreach ($blockedKeywords as $keyword) {
        if (strpos($searchLower, $keyword) !== false) {
            $error = "Search term contains restricted keywords.";
            break;
        }
    }
    
    if (!$error) {
        // VULNERABLE SQL Query with multiple protections
        $sql = "SELECT id, username, email, role FROM users WHERE username LIKE '%" . $search . "%' OR email LIKE '%" . $search . "%'";
        
        // Additional "protection" - this can be bypassed
        if (strpos($search, "'") !== false || strpos($search, '"') !== false) {
            $error = "Special characters are not allowed in search.";
        } else {
            $result = $conn->query($sql);
            
            if ($result && $result->num_rows > 0) {
                $users = [];
                while ($row = $result->fetch_assoc()) {
                    $users[] = $row;
                }
                $success = "Found " . count($users) . " user(s).";
            } else {
                $success = "No users found matching your search.";
            }
        }
    }
}

// Get all users for display
$allUsersSql = "SELECT id, username, email, role FROM users LIMIT 10";
$allUsersResult = $conn->query($allUsersSql);
$allUsers = [];
if ($allUsersResult && $allUsersResult->num_rows > 0) {
    while ($row = $allUsersResult->fetch_assoc()) {
        $allUsers[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Search System</title>
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
        input[type="text"] {
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .flag-section {
            background: #d4edda;
            color: #155724;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
            text-align: center;
        }
        .flag-section h3 {
            margin-top: 0;
        }
        .flag-content {
            font-family: monospace;
            font-size: 1.2em;
            font-weight: bold;
            background: #c3e6cb;
            padding: 10px;
            border-radius: 3px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 User Search System</h1>
        
        <div class="info">
            <h3>📋 System Information</h3>
            <p>Search for users by username or email. The system has multiple security layers to prevent unauthorized access.</p>
            <p><strong>Restrictions:</strong> Special characters and SQL keywords are blocked.</p>
        </div>
        
        <?php if ($success): ?>
            <div class="message success">
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="message error">
                <strong>Error:</strong> <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="search">Search Users:</label>
                <input type="text" name="search" id="search" placeholder="Enter username or email..." required>
            </div>
            <button type="submit">Search</button>
        </form>
        
        <h2>All Users (Sample)</h2>
        <?php if (!empty($allUsers)): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($allUsers as $user): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['id']); ?></td>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo htmlspecialchars($user['role']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No users found.</p>
        <?php endif; ?>
        
        <?php
        // Check if flag should be displayed (this is the vulnerability)
        if (isset($_POST['search'])) {
            $searchTerm = $_POST['search'];
            // This condition can be exploited through SQL injection
            if (strpos($searchTerm, 'admin') !== false || strpos($searchTerm, 'flag') !== false) {
                echo '<div class="flag-section">';
                echo '<h3>🏆 Flag Found!</h3>';
                echo '<p>Congratulations! You found the hidden flag.</p>';
                echo '<div class="flag-content">' . file_get_contents('flag.txt') . '</div>';
                echo '</div>';
            }
        }
        ?>
    </div>
</body>
</html>
