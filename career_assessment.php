<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'db.php';

// Check if student is logged in
if(!isset($_SESSION['student_id'])){
    header("Location: login.php");
    exit;
}

// Automatically redirect to the main assessment (skills selection)
$_SESSION['user_interests'] = []; // Set empty interests
header("Location: assessment.php");
exit;
?>