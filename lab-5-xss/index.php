<?php
// Lab 5: Advanced XSS Challenge - Insane Level
// Multiple layers of filtering and encoding

session_start();

// Create flag file
if (!file_exists('flag.txt')) {
    file_put_contents('flag.txt', 'CTF{xss_master_insane}');
}

$message = '';
$error = '';

// Handle comment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'])) {
    $comment = $_POST['comment'];
    
    // Multiple layers of filtering (intentionally bypassable)
    $comment = htmlspecialchars($comment, ENT_QUOTES, 'UTF-8');
    $comment = str_replace(['<script>', '</script>', 'javascript:', 'onload=', 'onerror='], '', $comment);
    $comment = preg_replace('/[<>]/', '', $comment);
    
    // Store comment
    $comments = isset($_SESSION['comments']) ? $_SESSION['comments'] : [];
    $comments[] = [
        'id' => count($comments) + 1,
        'comment' => $comment,
        'timestamp' => date('Y-m-d H:i:s')
    ];
    $_SESSION['comments'] = $comments;
    
    $message = "Comment submitted successfully!";
}

// Get comments
$comments = isset($_SESSION['comments']) ? $_SESSION['comments'] : [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comment System</title>
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
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .message.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .comment {
            background: #f8f9fa;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 5px;
            border-left: 4px solid #007bff;
        }
        .comment-meta {
            font-size: 0.9em;
            color: #666;
            margin-bottom: 10px;
        }
        .admin-panel {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .admin-panel h3 {
            margin-top: 0;
            color: #856404;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>💬 Comment System</h1>
        
        <div class="admin-panel">
            <h3>🔧 Admin Panel</h3>
            <p>Admin access: <a href="admin.php">Click here</a></p>
        </div>
        
        <?php if ($message): ?>
            <div class="message success">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="comment">Leave a Comment:</label>
                <textarea name="comment" id="comment" rows="4" required></textarea>
            </div>
            <button type="submit">Submit Comment</button>
        </form>
        
        <h2>Comments</h2>
        <?php if (empty($comments)): ?>
            <p>No comments yet. Be the first to comment!</p>
        <?php else: ?>
            <?php foreach (array_reverse($comments) as $comment): ?>
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
