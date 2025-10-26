<?php
// Lab 4: Dashboard - Shows flag after SQL injection bypass
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$username = $_SESSION['username'];
$role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }
        .dashboard-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            text-align: center;
        }
        h1 {
            color: #333;
            margin-bottom: 10px;
        }
        .user-info {
            color: #666;
            font-size: 16px;
        }
        .dashboard-content {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .flag-section {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
            color: white;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
            margin-bottom: 20px;
        }
        .flag-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 15px;
        }
        .flag-content {
            font-size: 20px;
            font-family: 'Courier New', monospace;
            background: rgba(255, 255, 255, 0.2);
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
        }
        .success-message {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
        .logout-btn {
            background: #dc3545;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
            display: inline-block;
        }
        .logout-btn:hover {
            background: #c82333;
        }
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .stat-number {
            font-size: 32px;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 10px;
        }
        .stat-label {
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="header">
            <h1>🎉 User Dashboard</h1>
            <div class="user-info">
                Welcome, <strong><?php echo htmlspecialchars($username); ?></strong>! 
                Role: <strong><?php echo htmlspecialchars($role); ?></strong>
            </div>
        </div>
        
        <div class="success-message">
            <strong>✅ Login Successful!</strong><br>
            You have successfully logged into the system.
        </div>
        
        <div class="flag-section">
            <div class="flag-title">🏆 FLAG CAPTURED!</div>
            <div class="flag-content">CTF{sql_injection_master}</div>
            <p>Welcome to your personal dashboard! You now have access to all system features.</p>
        </div>
        
        <div class="stats">
            <div class="stat-card">
                <div class="stat-number">1</div>
                <div class="stat-label">User Session</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">100%</div>
                <div class="stat-label">Success Rate</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">Admin</div>
                <div class="stat-label">Access Level</div>
            </div>
        </div>
        
        <div class="dashboard-content">
            <h2>🔍 System Information</h2>
            <p><strong>Session Type:</strong> User Authentication</p>
            <p><strong>Access Level:</strong> Standard User</p>
            <p><strong>Login Method:</strong> Username/Password</p>
            
            <h3>Available Features:</h3>
            <ul>
                <li>User profile management</li>
                <li>System settings access</li>
                <li>Data viewing permissions</li>
                <li>Account preferences</li>
            </ul>
            
            <h3>Security Notes:</h3>
            <ul>
                <li>Always use strong passwords</li>
                <li>Log out when finished</li>
                <li>Report suspicious activity</li>
                <li>Keep your account secure</li>
            </ul>
        </div>
        
        <div style="text-align: center;">
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </div>
</body>
</html>
