<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Career Guidance System</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: #f5f7fa;
            color: #2c3e50;
        }
        
        .navbar {
            background: #2c3e50 !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .sidebar {
            background: #34495e;
            min-height: 100vh;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            width: 260px;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
        }
        
        .sidebar .nav-link {
            color: #ecf0f1;
            padding: 12px 20px;
            margin: 5px 0;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .sidebar .nav-link:hover {
            background: #2c3e50;
            color: white;
            transform: translateX(5px);
        }
        
        .sidebar .nav-link.active {
            background: #3498db;
            color: white;
        }
        
        .stat-card {
            border-radius: 15px;
            border: none;
            transition: all 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        .btn {
            border-radius: 8px;
            font-weight: 500;
        }
        
        .card {
            border-radius: 12px;
            border: 2px solid #e8ecef;
        }
        
        /* Adjust container for proper spacing */
        .main-content {
            margin-left: 260px;
            padding: 20px;
            width: calc(100% - 260px);
        }
        
        /* Mobile responsiveness */
        @media (max-width: 768px) {
            body {
                padding-left: 0;
            }
            
            .sidebar {
                position: relative !important;
                width: 100%;
            }
            
            .main-content {
                margin-left: 0;
                width: 100%;
            }
        }
    </style>
</head>
<body>