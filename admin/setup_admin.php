<?php
// Simple script to create admin account
include '../db.php';

// Delete existing admin
$conn->query("DELETE FROM admins WHERE username='admin'");

// Create new admin with password: admin123
$username = 'admin';
$password = password_hash('admin123', PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
$stmt->bind_param("ss", $username, $password);

if($stmt->execute()){
    echo "<h2 style='color: green; font-family: Arial;'>✓ Admin account created successfully!</h2>";
    echo "<p><strong>Username:</strong> admin</p>";
    echo "<p><strong>Password:</strong> admin123</p>";
    echo "<br><a href='login.php' style='padding: 10px 20px; background: #3498db; color: white; text-decoration: none; border-radius: 5px;'>Go to Login</a>";
} else {
    echo "<h2 style='color: red;'>Error: " . $conn->error . "</h2>";
}

$stmt->close();
$conn->close();
?>
