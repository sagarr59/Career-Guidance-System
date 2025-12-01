<?php
include 'header.php';
if(!isset($_SESSION['admin_logged_in'])){ header("Location: login.php"); exit; }
include '../db.php';
include 'sidebar.php';

// Get counts
$careers_count = $conn->query("SELECT COUNT(*) as total FROM careers")->fetch_assoc()['total'];
$skills_count = $conn->query("SELECT COUNT(*) as total FROM skills")->fetch_assoc()['total'];
$assess_count = $conn->query("SELECT COUNT(*) as total FROM assessment_questions")->fetch_assoc()['total'];
$pers_count = $conn->query("SELECT COUNT(*) as total FROM personality_questions")->fetch_assoc()['total'];
$students_count = $conn->query("SELECT COUNT(*) as total FROM students")->fetch_assoc()['total'];

// Get recent students
$recent_students = $conn->query("SELECT * FROM students ORDER BY created_at DESC LIMIT 5");
?>

<div class="main-content">
    <!-- Welcome Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold">Welcome, <?= htmlspecialchars($_SESSION['admin_username']) ?>!</h2>
            <p class="text-muted">Career Guidance System - Admin Dashboard</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card shadow-sm" style="border-left: 4px solid #3498db;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Careers</h6>
                            <h2 class="fw-bold mb-0"><?= $careers_count ?></h2>
                        </div>
                        <div class="text-primary" style="font-size: 3rem; opacity: 0.3;">
                            <i class="fas fa-briefcase"></i>
                        </div>
                    </div>
                    <a href="manage_careers.php" class="btn btn-sm btn-primary mt-3">
                        <i class="fas fa-edit me-1"></i>Manage
                    </a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stat-card shadow-sm" style="border-left: 4px solid #27ae60;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Skills</h6>
                            <h2 class="fw-bold mb-0"><?= $skills_count ?></h2>
                        </div>
                        <div class="text-success" style="font-size: 3rem; opacity: 0.3;">
                            <i class="fas fa-tools"></i>
                        </div>
                    </div>
                    <a href="manage_skills.php" class="btn btn-sm btn-success mt-3">
                        <i class="fas fa-edit me-1"></i>Manage
                    </a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stat-card shadow-sm" style="border-left: 4px solid #e74c3c;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Questions</h6>
                            <h2 class="fw-bold mb-0"><?= ($pers_count + $assess_count) ?></h2>
                        </div>
                        <div class="text-danger" style="font-size: 3rem; opacity: 0.3;">
                            <i class="fas fa-clipboard-question"></i>
                        </div>
                    </div>
                    <a href="manage_personality.php" class="btn btn-sm btn-danger mt-3">
                        <i class="fas fa-edit me-1"></i>Manage
                    </a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stat-card shadow-sm" style="border-left: 4px solid #f39c12;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Students</h6>
                            <h2 class="fw-bold mb-0"><?= $students_count ?></h2>
                        </div>
                        <div class="text-warning" style="font-size: 3rem; opacity: 0.3;">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                    </div>
                    <a href="manage_students.php" class="btn btn-sm btn-warning mt-3">
                        <i class="fas fa-edit me-1"></i>Manage
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Students Table -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-user-graduate me-2"></i>Recent Students
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th width="50">#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Joined</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if($recent_students->num_rows > 0){
                                    $i = 1;
                                    while($student = $recent_students->fetch_assoc()){
                                        echo '<tr>
                                            <td>'.$i.'</td>
                                            <td>'.htmlspecialchars($student['name']).'</td>
                                            <td>'.htmlspecialchars($student['email']).'</td>
                                            <td>'.date('M j, Y', strtotime($student['created_at'])).'</td>
                                        </tr>';
                                        $i++;
                                    }
                                } else {
                                    echo '<tr><td colspan="4" class="text-center py-4">
                                        <i class="fas fa-user-plus fa-3x text-muted mb-3 d-block"></i>
                                        <p class="text-muted">No students registered yet.</p>
                                    </td></tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>