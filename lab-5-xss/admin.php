<?php
// Admin panel - vulnerable to XSS
session_start();

// Check if admin (simplified for demo)
$isAdmin = isset($_GET['admin']) && $_GET['admin'] === 'true';

if (!$isAdmin) {
    header('Location: index.php');
    exit;
}

// Get comments from session
$comments = isset($_SESSION['comments']) ? $_SESSION['comments'] : [];

// Handle admin actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    
    if ($action === 'clear') {
        $_SESSION['comments'] = [];
        $message = "All comments cleared!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
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
            color: #dc3545;
            text-align: center;
            margin-bottom: 30px;
        }
        .admin-info {
            background: #d1ecf1;
            border: 1px solid #bee5eb;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .comment {
            background: #f8f9fa;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 5px;
            border-left: 4px solid #dc3545;
        }
        .comment-meta {
            font-size: 0.9em;
            color: #666;
            margin-bottom: 10px;
        }
        button {
            background: #dc3545;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-right: 10px;
        }
        button:hover {
            background: #c82333;
        }
        .message {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 Admin Panel</h1>
        
        <div class="admin-info">
            <strong>Admin Access Granted</strong><br>
            You have full administrative privileges to manage comments.
        </div>
        
        <?php if (isset($message)): ?>
            <div class="message">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" style="margin-bottom: 30px;">
            <button type="submit" name="action" value="clear">Clear All Comments</button>
            <a href="index.php" style="background: #6c757d; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Back to Comments</a>
        </form>
        
        <h2>All Comments</h2>
        <?php if (empty($comments)): ?>
            <p>No comments to display.</p>
        <?php else: ?>
            <?php foreach ($comments as $comment): ?>
                <div class="comment">
                    <div class="comment-meta">
                        Comment #<?php echo $comment['id']; ?> - <?php echo $comment['timestamp']; ?>
                    </div>
                    <div class="comment-content">
                        <?php echo $comment['comment']; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>
