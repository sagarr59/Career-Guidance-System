<?php
session_start();

// Store a goodbye message
$_SESSION = array();

// Destroy the session cookie
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-42000, '/');
}

// Destroy session
session_destroy();

// Start new session for message
session_start();
$_SESSION['logout_message'] = 'You have been logged out successfully. See you again!';

// Redirect to homepage
header("Location: index.php");
exit;
?>
