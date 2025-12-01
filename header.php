<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?><!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Career Guidance System - Nepal</title>
  
  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <!-- Font Awesome 6 -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  
  <!-- Custom CSS -->
  <link href="assets/css/style.css" rel="stylesheet">
  
  <style>
    :root {
      --primary-color: #2c3e50;
      --secondary-color: #e74c3c;
      --accent-color: #3498db;
      --success-color: #27ae60;
      --warning-color: #f39c12;
      --bg-light: #ecf0f1;
      --text-dark: #2c3e50;
      --text-light: #7f8c8d;
    }
    
    * { margin: 0; padding: 0; box-sizing: border-box; }
    
    body { 
      background: #f5f7fa;
      color: var(--text-dark); 
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      overflow-x: hidden;
    }
    
    .navbar { 
      background: var(--primary-color) !important;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .navbar-brand { 
      color: #fff !important; 
      font-weight: 700; 
      font-size: 1.5rem;
      letter-spacing: 0.5px;
      animation: pulse 2s infinite;
    }
    
    .navbar-nav .nav-link { 
      color: rgba(255,255,255,0.85) !important; 
      font-weight: 500;
      margin: 0 10px;
      position: relative;
      transition: all 0.3s ease;
    }
    
    .navbar-nav .nav-link:hover {
      color: #fff !important;
      transform: translateY(-2px);
    }
    
    .navbar-nav .nav-link.active { 
      color: #fff !important;
      font-weight: 700;
    }
    
    .navbar-nav .nav-link.active::after {
      content: '';
      position: absolute;
      bottom: -5px;
      left: 50%;
      transform: translateX(-50%);
      width: 30px;
      height: 3px;
      background: var(--secondary-color);
      border-radius: 10px;
    }
    
    .card { 
      background-color: #fff; 
      border-radius: 15px;
      border: 2px solid #e8ecef;
      transition: all 0.3s ease;
    }
    
    .btn-primary { 
      background: var(--accent-color);
      border: none;
      padding: 12px 30px;
      border-radius: 8px;
      font-weight: 600;
      transition: all 0.3s ease;
    }
    
    .btn-primary:hover { 
      background: #2980b9;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);
    }
    
    .btn-success { 
      background: var(--success-color);
      border: none;
      padding: 12px 30px;
      border-radius: 8px;
      font-weight: 600;
      transition: all 0.3s ease;
    }
    
    .btn-success:hover { 
      background: #229954;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(39, 174, 96, 0.3);
    }
    
    .btn-warning {
      background: var(--warning-color);
      border: none;
      color: white;
      padding: 12px 30px;
      border-radius: 8px;
      font-weight: 600;
      transition: all 0.3s ease;
    }
    
    .btn-warning:hover { 
      background: #e67e22;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(243, 156, 18, 0.3);
    }
    
    .btn-danger {
      background: var(--secondary-color);
      border: none;
      padding: 12px 30px;
      border-radius: 8px;
      font-weight: 600;
      transition: all 0.3s ease;
    }
    
    .btn-danger:hover { 
      background: #c0392b;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(231, 76, 60, 0.3);
    }
    
    /* Career Path Generator Styles */
    #network, #fullNetwork {
      width: 100%;
      height: 500px;
      border: 2px solid #3498db;
      border-radius: 15px;
      background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
      position: relative;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
      overflow: hidden;
      margin-bottom: 20px;
    }
    
    #fullNetwork {
      height: 600px;
      border: 2px solid #27ae60;
    }
    
    .network-info {
      background: linear-gradient(135deg, #e1f0fa 0%, #d1e7f5 100%);
      border-radius: 15px;
      padding: 2rem;
      margin-top: 2rem;
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
      border: 2px solid rgba(52, 152, 219, 0.3);
    }
    
    .network-info h6 {
      font-size: 1.2rem;
      font-weight: 700;
      color: #2c3e50;
      margin-bottom: 1rem;
      text-align: center;
    }
    
    .network-legend {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      margin-top: 20px;
      justify-content: center;
    }
    
    .legend-item {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 12px 20px;
      background: rgba(255, 255, 255, 0.9);
      border-radius: 25px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      transition: all 0.3s ease;
      font-weight: 600;
      font-size: 1.1rem;
    }
    
    .legend-item:hover {
      transform: translateY(-3px);
      box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
      background: rgba(255, 255, 255, 1);
    }
    
    .legend-color {
      width: 30px;
      height: 30px;
      border-radius: 50%;
      box-shadow: 0 3px 8px rgba(0, 0, 0, 0.25);
      border: 2px solid white;
    }
    
    .node-label {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      font-size: 14px;
      font-weight: bold;
      color: #000;
      text-shadow: 1px 1px 3px rgba(255,255,255,0.9);
      padding: 4px 8px;
      border-radius: 6px;
      background: rgba(255, 255, 255, 0.85);
      backdrop-filter: blur(5px);
    }
    
    /* Results Page Styles */
    .results-header {
      background: #2c3e50;
      color: white;
      border-radius: 15px;
      margin-bottom: 2rem;
      padding: 2rem;
      box-shadow: 0 10px 30px rgba(0,0,0,0.15);
      position: relative;
      overflow: hidden;
    }
    
    .score-card {
      background: white;
      border-radius: 15px;
      padding: 2rem;
      text-align: center;
      box-shadow: 0 10px 30px rgba(0,0,0,0.1);
      transition: all 0.3s ease;
      height: 100%;
      position: relative;
      overflow: hidden;
    }
    
    .score-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 15px 40px rgba(0,0,0,0.15);
    }
    
    .career-card {
      background: white;
      border-radius: 15px;
      padding: 2rem;
      box-shadow: 0 10px 30px rgba(0,0,0,0.1);
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
      margin-bottom: 2rem;
    }
    
    .career-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 15px 40px rgba(0,0,0,0.15);
    }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark shadow-sm sticky-top">
  <div class="container">
    <a class="navbar-brand" href="index.php">
      <i class="fas fa-graduation-cap me-2"></i>Career Guidance
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link <?= basename($_SERVER['PHP_SELF'])=='index.php'?'active':'' ?>" href="index.php"><i class="fas fa-home me-1"></i>Home</a></li>
        <li class="nav-item"><a class="nav-link <?= basename($_SERVER['PHP_SELF'])=='career_explorer.php'?'active':'' ?>" href="career_explorer.php"><i class="fas fa-compass me-1"></i>Career Explorer</a></li>
        <?php if(isset($_SESSION['student_id'])): ?>
          <li class="nav-item"><a class="nav-link <?= basename($_SERVER['PHP_SELF'])=='career_assessment.php'?'active':'' ?>" href="career_assessment.php"><i class="fas fa-clipboard-list me-1"></i>Career Assessment</a></li>
                    <li class="nav-item"><a class="nav-link <?= basename($_SERVER['PHP_SELF'])=='career_path_generator.php'?'active':'' ?>" href="career_path_generator.php"><i class="fas fa-network-wired me-1"></i>Career Path</a></li>
          <li class="nav-item"><a class="nav-link <?= basename($_SERVER['PHP_SELF'])=='results.php'?'active':'' ?>" href="results.php"><i class="fas fa-chart-line me-1"></i>Results</a></li>
          <li class="nav-item"><a class="nav-link <?= basename($_SERVER['PHP_SELF'])=='info.php'?'active':'' ?>" href="info.php"><i class="fas fa-info-circle me-1"></i>Info</a></li>
          <li class="nav-item"><a class="nav-link <?= basename($_SERVER['PHP_SELF'])=='userprofile.php'?'active':'' ?>" href="userprofile.php"><i class="fas fa-user me-1"></i>Profile</a></li>
          <li class="nav-item"><a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt me-1"></i>Logout</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link <?= basename($_SERVER['PHP_SELF'])=='career_explorer.php'?'active':'' ?>" href="career_explorer.php"><i class="fas fa-compass me-1"></i>Career Explorer</a></li>
          <li class="nav-item"><a class="nav-link <?= basename($_SERVER['PHP_SELF'])=='career_assessment.php'?'active':'' ?>" href="career_assessment.php"><i class="fas fa-clipboard-list me-1"></i>Career Assessment</a></li>
          <li class="nav-item"><a class="nav-link <?= basename($_SERVER['PHP_SELF'])=='info.php'?'active':'' ?>" href="info.php"><i class="fas fa-info-circle me-1"></i>Info</a></li>
          <li class="nav-item"><a class="nav-link <?= basename($_SERVER['PHP_SELF'])=='login.php'?'active':'' ?>" href="login.php"><i class="fas fa-sign-in-alt me-1"></i>Login</a></li>
          <li class="nav-item"><a class="nav-link <?= basename($_SERVER['PHP_SELF'])=='register.php'?'active':'' ?>" href="register.php"><i class="fas fa-user-plus me-1"></i>Sign Up</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>