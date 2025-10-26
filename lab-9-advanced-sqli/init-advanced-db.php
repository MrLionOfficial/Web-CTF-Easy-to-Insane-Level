<?php
$servername = "db";
$username = "root";
$password = "password";
$dbname = "advanced_sqli_lab";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully or already exists\n";
} else {
    echo "Error creating database: " . $conn->error . "\n";
}

$conn->select_db($dbname);

// Create users table
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(30) NOT NULL UNIQUE,
    email VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(30) NOT NULL DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
if ($conn->query($sql) === TRUE) {
    echo "Table users created successfully or already exists\n";
} else {
    echo "Error creating table: " . $conn->error . "\n";
}

// Insert sample users
$users = [
    ['admin', 'admin@system.com', 'Kj8#mN2$pL9@vX7!qR4&wE6*tY3^uI1', 'admin'],
    ['john', 'john@example.com', 'Bx5%nM8#sP2$dF9@kL6&hJ4*tG7^vC3', 'user'],
    ['jane', 'jane@example.com', 'Qw3$eR5%tY7^uI9@oP2&aS6*dF8!gH4', 'user'],
    ['bob', 'bob@example.com', 'Mn7#bV2$cX9@zL5&kP8*tR4^wE6!qA3', 'user'],
    ['alice', 'alice@example.com', 'Rt9$yU6%iO3@eW8&qT5*uR7^pA2!sD4', 'user']
];

foreach ($users as $user) {
    $check_sql = "SELECT id FROM users WHERE username = '" . $user[0] . "'";
    $result = $conn->query($check_sql);
    
    if ($result->num_rows == 0) {
        $insert_sql = "INSERT INTO users (username, email, password, role) VALUES ('" . $user[0] . "', '" . $user[1] . "', '" . $user[2] . "', '" . $user[3] . "')";
        if ($conn->query($insert_sql) === TRUE) {
            echo "User " . $user[0] . " inserted successfully\n";
        } else {
            echo "Error inserting user " . $user[0] . ": " . $conn->error . "\n";
        }
    } else {
        echo "User " . $user[0] . " already exists\n";
    }
}

$conn->close();
?>
