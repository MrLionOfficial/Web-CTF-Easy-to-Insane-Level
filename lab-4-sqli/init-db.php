<?php
// Lab 4: Database initialization script
// This script creates the database and tables for the SQL injection lab

$host = 'db';
$username = 'root';
$password = 'password';

try {
    // Connect to MySQL server (without specifying database)
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create database if it doesn't exist
    $pdo->exec("CREATE DATABASE IF NOT EXISTS sqli_lab");
    $pdo->exec("USE sqli_lab");
    
    // Create users table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            role VARCHAR(20) DEFAULT 'user',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");
    
    // Insert sample users
    $users = [
        ['admin', 'admin123', 'admin'],
        ['user1', 'password123', 'user'],
        ['test', 'test123', 'user'],
        ['john', 'john123', 'user']
    ];
    
    // Clear existing users first
    $pdo->exec("DELETE FROM users");
    
    // Insert users
    $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    foreach ($users as $user) {
        $stmt->execute($user);
    }
    
    echo "Database initialized successfully!\n";
    echo "Users created:\n";
    foreach ($users as $user) {
        echo "- Username: {$user[0]}, Password: {$user[1]}, Role: {$user[2]}\n";
    }
    
} catch (PDOException $e) {
    echo "Database initialization failed: " . $e->getMessage() . "\n";
}
?>
