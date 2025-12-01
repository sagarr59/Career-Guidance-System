<?php
include 'header.php';
if(!isset($_SESSION['admin_logged_in'])){
    header("Location: login.php");
    exit;
}

include '../db.php';
include 'sidebar.php';

// Check if we're editing a skill
$editing = false;
$skill = null;
if(isset($_GET['edit'])){
    $editing = true;
    $id = intval($_GET['edit']);
    $skill = $conn->query("SELECT * FROM skills WHERE id=$id")->fetch_assoc();
    if(!$skill){
        header("Location: manage_skills.php"); exit;
    }
}

// Handle Add Skill
if(isset($_POST['add_skill'])){
    $skill_name = trim($_POST['skill_name']);
    if(empty($skill_name)){
        $error = "Skill name cannot be empty.";
    } else {
        // Prevent duplicate
        $stmt = $conn->prepare("SELECT id FROM skills WHERE name=?");
        $stmt->bind_param("s", $skill_name);
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows > 0){
            $error = "Skill already exists.";
        } else {
            $stmt = $conn->prepare("INSERT INTO skills (name) VALUES (?)");
            $stmt->bind_param("s", $skill_name);
            $stmt->execute();
            $success = "Skill added successfully!";
        }
    }
}

// Handle Update Skill
if(isset($_POST['update_skill'])){
    $id = intval($_POST['id']);
    $skill_name = trim($_POST['skill_name']);
    if(empty($skill_name)){
        $error = "Skill name cannot be empty.";
    } else {
        $stmt = $conn->prepare("UPDATE skills SET name=? WHERE id=?");
        $stmt->bind_param("si",$skill_name,$id);
        $stmt->execute();
        $success = "Skill updated successfully!";
        
        // Refresh skill data
        $skill = $conn->query("SELECT * FROM skills WHERE id=$id")->fetch_assoc();
    }
}

// Handle Delete Skill
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM skills WHERE id=$id");
    $success = "Skill deleted successfully!";
}
?>

<div class="main-content">
    <div class="mb-4">
        <h2 class="fw-bold"><i class="fas fa-tools me-2"></i><?= $editing ? 'Edit Skill' : 'Manage Skills' ?></h2>
        <p class="text-muted"><?= $editing ? 'Update skill information' : 'Add, edit, and manage skills for career assessments' ?></p>
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
    <!-- Add Skill Form -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Add New Skill</h5>
        </div>
        <div class="card-body">
            <form method="POST" class="row g-3">
                <div class="col-md-10">
                    <label for="skill_name" class="form-label fw-semibold">Skill Name</label>
                    <input type="text" name="skill_name" id="skill_name" class="form-control form-control-lg" placeholder="e.g., Python, Communication, Leadership" required>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" name="add_skill" class="btn btn-success w-100 btn-lg">
                        <i class="fas fa-plus me-1"></i>Add
                    </button>
                </div>
            </form>
        </div>
    </div>
    <?php else: ?>
    <!-- Edit Skill Form -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white">
            <h5 class="mb-0 fw-bold">Skill Details</h5>
        </div>
        <div class="card-body">
            <form method="POST">
                <input type="hidden" name="id" value="<?= $skill['id'] ?>">
                <div class="mb-3">
                    <label for="skill_name" class="form-label fw-semibold">Skill Name</label>
                    <input type="text" name="skill_name" id="skill_name" class="form-control form-control-lg" value="<?= htmlspecialchars($skill['name']) ?>" required>
                </div>
                <button type="submit" name="update_skill" class="btn btn-primary btn-lg">
                    <i class="fas fa-save me-2"></i>Update Skill
                </button>
                <a href="manage_skills.php" class="btn btn-secondary btn-lg">
                    <i class="fas fa-arrow-left me-2"></i>Back to Skills
                </a>
            </form>
        </div>
    </div>
    <?php endif; ?>

    <!-- Skills Table -->
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0 fw-bold"><i class="fas fa-list me-2"></i>All Skills</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th width="80">#</th>
                            <th>Skill Name</th>
                            <th width="200" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $skills = $conn->query("SELECT * FROM skills ORDER BY name ASC");
                        if($skills->num_rows > 0){
                            $i = 1;
                            while($row = $skills->fetch_assoc()){
                                echo "<tr>
                                    <td>{$i}</td>
                                    <td>
                                        <i class='fas fa-wrench me-2 text-success'></i>
                                        <strong>" . htmlspecialchars($row['name']) . "</strong>
                                    </td>
                                    <td class='text-end'>
                                        <a href='manage_skills.php?edit={$row['id']}' class='btn btn-sm btn-primary'>
                                            <i class='fas fa-edit'></i> Edit
                                        </a>
                                        <a href='manage_skills.php?delete={$row['id']}' class='btn btn-sm btn-danger' onclick='return confirm(\"Delete this skill?\")'>  
                                            <i class='fas fa-trash'></i> Delete
                                        </a>
                                    </td>
                                </tr>";
                                $i++;
                            }
                        } else {
                            echo "<tr><td colspan='3' class='text-center py-4'>
                                <i class='fas fa-inbox fa-3x text-muted mb-3 d-block'></i>
                                <p class='text-muted'>No skills added yet. Add your first skill above!</p>
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
