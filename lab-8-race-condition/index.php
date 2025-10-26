<?php
// Lab 8: Race Condition Challenge - Insane Level
// Time-based race condition vulnerability

// Create flag file
if (!file_exists('flag.txt')) {
    file_put_contents('flag.txt', 'CTF{race_condition_master_insane}');
}

// Create balance file
if (!file_exists('balance.txt')) {
    file_put_contents('balance.txt', '1000');
}

$message = '';
$error = '';
$balance = 0;

// Handle withdrawal request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['amount'])) {
    $amount = (int)$_POST['amount'];
    
    if ($amount <= 0) {
        $error = "Amount must be positive.";
    } else {
        // Read current balance
        $currentBalance = (int)file_get_contents('balance.txt');
        
        // Simulate processing delay (vulnerability window)
        usleep(100000); // 0.1 second delay
        
        // Check if sufficient funds
        if ($currentBalance >= $amount) {
            // Calculate new balance
            $newBalance = $currentBalance - $amount;
            
            // Write new balance
            file_put_contents('balance.txt', $newBalance);
            
            $message = "Withdrawal successful! Amount: $" . $amount;
            
            // Check if balance is exactly 0 (flag condition)
            if ($newBalance == 0) {
                $message .= "<br><br><div style='background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
                $message .= "<h3>🏆 Perfect Balance Achieved!</h3>";
                $message .= "<p><strong>Flag:</strong> " . file_get_contents('flag.txt') . "</p>";
                $message .= "</div>";
            }
        } else {
            $error = "Insufficient funds. Current balance: $" . $currentBalance;
        }
    }
}

// Get current balance
$balance = (int)file_get_contents('balance.txt');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Banking System</title>
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
        .balance-display {
            background: #d1ecf1;
            border: 1px solid #bee5eb;
            padding: 20px;
            margin-bottom: 30px;
            border-radius: 5px;
            text-align: center;
        }
        .balance-display h2 {
            margin-top: 0;
            color: #0c5460;
        }
        .balance-amount {
            font-size: 2em;
            font-weight: bold;
            color: #007bff;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="number"] {
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
        .challenge-info {
            background: #f8d9fa;
            border: 1px solid #e2a3e5;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .challenge-info h3 {
            margin-top: 0;
            color: #6f42c1;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🏦 Banking System</h1>
        
        <div class="balance-display">
            <h2>Current Balance</h2>
            <div class="balance-amount">$<?php echo $balance; ?></div>
        </div>
        
        <div class="challenge-info">
            <h3>🎯 Challenge Objective</h3>
            <p>Withdraw money to achieve a balance of exactly $0 to get the flag!</p>
        </div>
        
        <div class="info">
            <h3>📋 System Information</h3>
            <p>This banking system processes withdrawal requests. Each withdrawal reduces your balance by the specified amount.</p>
            <p><strong>Note:</strong> The system has a processing delay of 0.1 seconds per transaction.</p>
        </div>
        
        <?php if ($message): ?>
            <div class="message success">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="message error">
                <strong>Error:</strong> <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="amount">Withdrawal Amount ($):</label>
                <input type="number" name="amount" id="amount" min="1" max="<?php echo $balance; ?>" required>
            </div>
            <button type="submit">Withdraw</button>
        </form>
        
        <div style="margin-top: 30px; text-align: center;">
            <button onclick="location.reload()" style="background: #6c757d;">Refresh Balance</button>
        </div>
    </div>
</body>
</html>
