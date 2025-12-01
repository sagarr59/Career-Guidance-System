<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$servername = "localhost";  // usually localhost
$username = "root";         // your MySQL username
$password = "";             // your MySQL password
$dbname = "career_guidance"; // your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
