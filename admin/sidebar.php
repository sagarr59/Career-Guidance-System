<?php
$current_page = basename($_SERVER['PHP_SELF']);
$careers_count = $conn->query("SELECT COUNT(*) as total FROM careers")->fetch_assoc()['total'];
$skills_count = $conn->query("SELECT COUNT(*) as total FROM skills")->fetch_assoc()['total'];
$assess_count = $conn->query("SELECT COUNT(*) as total FROM assessment_questions")->fetch_assoc()['total'];
$pers_count = $conn->query("SELECT COUNT(*) as total FROM personality_questions")->fetch_assoc()['total'];
$students_count = $conn->query("SELECT COUNT(*) as total FROM students")->fetch_assoc()['total'];
?>

<!-- Sidebar -->
    <nav class="sidebar">
        <div class="p-4">
            <h4 class="text-white fw-bold mb-4">
                <i class="fas fa-user-shield me-2"></i>Admin Panel
            </h4>
            
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="index.php" class="nav-link <?= $current_page=='index.php' ? 'active' : '' ?>">
                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="manage_careers.php" class="nav-link <?= $current_page=='manage_careers.php' ? 'active' : '' ?>">
                        <i class="fas fa-briefcase me-2"></i>Careers
                    </a>
                </li>
                <li class="nav-item">
                    <a href="manage_skills.php" class="nav-link <?= $current_page=='manage_skills.php' ? 'active' : '' ?>">
                        <i class="fas fa-tools me-2"></i>Skills
                    </a>
                </li>
                <li class="nav-item">
                    <a href="manage_assessments.php" class="nav-link <?= $current_page=='manage_assessments.php' ? 'active' : '' ?>">
                        <i class="fas fa-clipboard-question me-2"></i>Assessment Questions
                    </a>
                </li>
                <li class="nav-item">
                    <a href="manage_personality.php" class="nav-link <?= $current_page=='manage_personality.php' ? 'active' : '' ?>">
                        <i class="fas fa-head-side-virus me-2"></i>Personality Questions
                    </a>
                </li>

                <li class="nav-item">
                    <a href="manage_students.php" class="nav-link <?= $current_page=='manage_students.php' ? 'active' : '' ?>">
                        <i class="fas fa-users me-2"></i>Students
                    </a>
                </li>
            </ul>
            
            <hr class="my-4" style="opacity: 0.2;">
            
            <ul class="nav nav-pills flex-column">
                <li class="nav-item">
                    <a href="logout.php" class="nav-link text-danger">
                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                    </a>
                </li>
            </ul>
        </div>
    </nav>