<?php
include 'header.php';
if(!isset($_SESSION['admin_logged_in'])){
    header("Location: login.php"); exit;
}
include '../db.php';
include 'sidebar.php';

// Check if we're editing a career
$editing = false;
$career = null;
if(isset($_GET['edit'])){
    $editing = true;
    $id = intval($_GET['edit']);
    $career = $conn->query("SELECT * FROM careers WHERE id=$id")->fetch_assoc();
    if(!$career) header("Location: manage_careers.php");
}

// Handle Add Career
if(isset($_POST['add_career'])){
    $career_name = trim($_POST['career_name']);
    $description = trim($_POST['description']);
    $required_skills = trim($_POST['required_skills']);
    $salary_range = trim($_POST['salary_range']);
    
    if(empty($career_name)){
        $error = "Career name cannot be empty.";
    } else {
        $stmt = $conn->prepare("INSERT INTO careers (career_name, description, required_skills, salary_range) VALUES (?,?,?,?)");
        $stmt->bind_param("ssss",$career_name,$description,$required_skills,$salary_range);
        $stmt->execute();
        $success = "Career added successfully!";
    }
}

// Handle Update Career
if(isset($_POST['update_career'])){
    $id = intval($_POST['id']);
    $career_name = trim($_POST['career_name']);
    $description = trim($_POST['description']);
    $required_skills = trim($_POST['required_skills']);
    $salary_range = trim($_POST['salary_range']);
    
    if(empty($career_name)){
        $error = "Career name cannot be empty";
    } else {
        $stmt = $conn->prepare("UPDATE careers SET career_name=?, description=?, required_skills=?, salary_range=? WHERE id=?");
        $stmt->bind_param("ssssi",$career_name,$description,$required_skills,$salary_range,$id);
        $stmt->execute();
        $success = "Career updated successfully";
        
        // Refresh career data
        $career = $conn->query("SELECT * FROM careers WHERE id=$id")->fetch_assoc();
    }
}

// Handle Delete
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM careers WHERE id=$id");
    $success = "Career deleted successfully!";
}
?>

<div class="main-content">
    <div class="mb-4">
        <h2 class="fw-bold"><i class="fas fa-briefcase me-2"></i><?= $editing ? 'Edit Career' : 'Manage Careers' ?></h2>
        <p class="text-muted"><?= $editing ? 'Update career information' : 'Add, edit, and manage career options for students' ?></p>
    </div>

    <?php if(isset($error)): ?>
        <div class='alert alert-danger alert-dismissible fade show'>
            <i class="fas fa-exclamation-circle me-2"></i><?= $error ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <?php if(isset($success)): ?>
        <div class='alert alert-success alert-dismissible fade show'>
            <i class="fas fa-check-circle me-2"></i><?= $success ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if(!$editing): ?>
    <!-- Add Career Form -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Add New Career</h5>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="career_name" class="form-label fw-semibold">Career Name</label>
                        <input type="text" name="career_name" id="career_name" class="form-control" placeholder="e.g., Software Engineer" required>
                    </div>
                    <div class="col-md-6">
                        <label for="salary_range" class="form-label fw-semibold">Salary Range</label>
                        <input type="text" name="salary_range" id="salary_range" class="form-control" placeholder="e.g., NPR 50,000 - 150,000/month">
                    </div>
                    <div class="col-md-12">
                        <label for="description" class="form-label fw-semibold">Description</label>
                        <textarea name="description" id="description" class="form-control" rows="3" placeholder="Describe the career"></textarea>
                    </div>
                    <div class="col-md-12">
                        <label for="required_skills" class="form-label fw-semibold">Required Skills</label>
                        <input type="text" name="required_skills" id="required_skills" class="form-control" placeholder="e.g., Programming, Problem Solving, Communication">
                        <small class="text-muted">Separate skills with commas</small>
                    </div>
                    <div class="col-12">
                        <button type="submit" name="add_career" class="btn btn-primary btn-lg">
                            <i class="fas fa-plus me-2"></i>Add Career
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php else: ?>
    <!-- Edit Career Form -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white">
            <h5 class="mb-0 fw-bold">Career Details</h5>
        </div>
        <div class="card-body">
            <form method="POST">
                <input type="hidden" name="id" value="<?= $career['id'] ?>">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="career_name" class="form-label fw-semibold">Career Name</label>
                        <input type="text" name="career_name" id="career_name" class="form-control" value="<?=htmlspecialchars($career['career_name'])?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="salary_range" class="form-label fw-semibold">Salary Range</label>
                        <input type="text" name="salary_range" id="salary_range" class="form-control" value="<?=htmlspecialchars($career['salary_range'])?>">
                    </div>
                    <div class="col-md-12">
                        <label for="description" class="form-label fw-semibold">Description</label>
                        <textarea name="description" id="description" class="form-control" rows="4"><?=htmlspecialchars($career['description'])?></textarea>
                    </div>
                    <div class="col-md-12">
                        <label for="required_skills" class="form-label fw-semibold">Required Skills</label>
                        <input type="text" name="required_skills" id="required_skills" class="form-control" value="<?=htmlspecialchars($career['required_skills'])?>">
                        <small class="text-muted">Separate skills with commas</small>
                    </div>
                    <div class="col-12">
                        <button type="submit" name="update_career" class="btn btn-primary btn-lg">
                            <i class="fas fa-save me-2"></i>Update Career
                        </button>
                        <a href="manage_careers.php" class="btn btn-secondary btn-lg">
                            <i class="fas fa-arrow-left me-2"></i>Back to Careers
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>

    <!-- Careers Table -->
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0 fw-bold"><i class="fas fa-list me-2"></i>All Careers</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th width="50">#</th>
                            <th>Career Name</th>
                            <th>Description</th>
                            <th>Salary Range</th>
                            <th width="200" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $careers = $conn->query("SELECT * FROM careers ORDER BY career_name ASC");
                        if($careers->num_rows > 0){
                            $i = 1;
                            while($row = $careers->fetch_assoc()){
                                $desc = htmlspecialchars(substr($row['description'], 0, 50)) . '...';
                                echo "<tr>
                                    <td>{$i}</td>
                                    <td>
                                        <i class='fas fa-graduation-cap me-2 text-primary'></i>
                                        <strong>" . htmlspecialchars($row['career_name']) . "</strong>
                                    </td>
                                    <td><small>{$desc}</small></td>
                                    <td><span class='badge bg-success'>" . htmlspecialchars($row['salary_range']) . "</span></td>
                                    <td class='text-end'>
                                        <a href='manage_careers.php?edit={$row['id']}' class='btn btn-sm btn-primary'>
                                            <i class='fas fa-edit'></i> Edit
                                        </a>
                                        <a href='manage_careers.php?delete={$row['id']}' class='btn btn-sm btn-danger' onclick='return confirm(\"Delete this career?\")'>  
                                            <i class='fas fa-trash'></i> Delete
                                        </a>
                                    </td>
                                </tr>";
                                $i++;
                            }
                        } else {
                            echo "<tr><td colspan='5' class='text-center py-4'>
                                <i class='fas fa-inbox fa-3x text-muted mb-3 d-block'></i>
                                <p class='text-muted'>No careers yet. Add your first career above!</p>
                            </td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>