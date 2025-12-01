<?php
include 'header.php';
if(!isset($_SESSION['admin_logged_in'])){ header("Location: login.php"); exit; }
include '../db.php';
include 'sidebar.php';

// Handle delete
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM students WHERE id = $id");
    header("Location: manage_students.php");
    exit;
}

// Get all students
$students = $conn->query("SELECT * FROM students ORDER BY created_at DESC");
?>

<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold"><i class="fas fa-users me-2"></i>Manage Students</h2>
            <p class="text-muted">View and manage all registered students</p>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Registered</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($students->num_rows > 0): ?>
                            <?php while($student = $students->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $student['id'] ?></td>
                                    <td>
                                        <i class="fas fa-user-circle me-2 text-primary"></i>
                                        <strong><?= htmlspecialchars($student['name']) ?></strong>
                                    </td>
                                    <td><?= htmlspecialchars($student['email']) ?></td>
                                    <td>
                                        <i class="fas fa-calendar me-1 text-muted"></i>
                                        <?= date('M d, Y - h:i A', strtotime($student['created_at'])) ?>
                                    </td>
                                    <td>
                                        <a href="?delete=<?= $student['id'] ?>" 
                                           class="btn btn-sm btn-danger" 
                                           onclick="return confirm('Delete this student?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <i class="fas fa-user-slash fa-3x text-muted mb-3 d-block"></i>
                                    <p class="text-muted">No students registered yet</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>