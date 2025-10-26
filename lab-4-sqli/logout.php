<?php
// Lab 4: Logout functionality
session_start();

// Destroy session
session_destroy();

// Redirect to login page
header('Location: index.php');
exit;
?>
