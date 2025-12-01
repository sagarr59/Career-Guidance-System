<?php
include 'header.php';
if(!isset($_SESSION['admin_logged_in'])){ header("Location: login.php"); exit; }
include '../db.php';
include 'sidebar.php';

// Check if we're editing an assessment question
$editing = false;
$question = null;
if(isset($_GET['edit'])){
    $editing = true;
    $id = intval($_GET['edit']);
    $question = $conn->query("SELECT * FROM assessment_questions WHERE id=$id")->fetch_assoc();
    if(!$question) header("Location: manage_assessments.php");
    
    // Parse options from JSON for editing
    $options = json_decode($question['options'], true);
    $option1 = isset($options[0]['option']) ? $options[0]['option'] : '';
    $option2 = isset($options[1]['option']) ? $options[1]['option'] : '';
    $option3 = isset($options[2]['option']) ? $options[2]['option'] : '';
    $option4 = isset($options[3]['option']) ? $options[3]['option'] : '';
}

// Helper function to format options preview
function formatOptionsPreview($optionsJson) {
    $options = json_decode($optionsJson, true);
    if (!$options || !is_array($options)) {
        return 'No options';
    }
    
    $preview = [];
    foreach (array_slice($options, 0, 2) as $option) {
        $preview[] = $option['option'];
    }
    return htmlspecialchars(implode(', ', $preview));
}

// Add Assessment Question
if(isset($_POST['add_assessment'])){
    $question = trim($_POST['question']);
    $option1 = trim($_POST['option1']);
    $option2 = trim($_POST['option2']);
    $option3 = trim($_POST['option3']);
    $option4 = trim($_POST['option4']);
    
    if(empty($question) || empty($option1) || empty($option2)){
        $error="Question and first two options are required";
    } else {
        // Convert options to JSON format
        $options = [
            ['option' => $option1, 'weight' => 1],
            ['option' => $option2, 'weight' => 2]
        ];
        
        if(!empty($option3)) {
            $options[] = ['option' => $option3, 'weight' => 3];
        }
        
        if(!empty($option4)) {
            $options[] = ['option' => $option4, 'weight' => 4];
        }
        
        $optionsJson = json_encode($options);
        
        $stmt=$conn->prepare("INSERT INTO assessment_questions (question, options) VALUES (?,?)");
        $stmt->bind_param("ss",$question,$optionsJson);
        $stmt->execute();
        $success="Assessment question added successfully!";
    }
}

// Update Assessment Question
if(isset($_POST['update_assessment'])){
    $id = intval($_POST['id']);
    $q = trim($_POST['question']);
    $opt1 = trim($_POST['option1']);
    $opt2 = trim($_POST['option2']);
    $opt3 = trim($_POST['option3']);
    $opt4 = trim($_POST['option4']);
    
    if(empty($q) || empty($opt1) || empty($opt2)){
        $error = "Question and first two options are required.";
    } else {
        // Convert options to JSON format
        $options = [
            ['option' => $opt1, 'weight' => 1],
            ['option' => $opt2, 'weight' => 2]
        ];
        
        if(!empty($opt3)) {
            $options[] = ['option' => $opt3, 'weight' => 3];
        }
        
        if(!empty($opt4)) {
            $options[] = ['option' => $opt4, 'weight' => 4];
        }
        
        $optionsJson = json_encode($options);
        
        $stmt = $conn->prepare("UPDATE assessment_questions SET question=?, options=? WHERE id=?");
        $stmt->bind_param("ssi",$q,$optionsJson,$id);
        $stmt->execute();
        $success = "Question updated successfully!";
        
        // Refresh question data
        $question = $conn->query("SELECT * FROM assessment_questions WHERE id=$id")->fetch_assoc();
        
        // Re-parse options for form display
        $options = json_decode($question['options'], true);
        $option1 = isset($options[0]['option']) ? $options[0]['option'] : '';
        $option2 = isset($options[1]['option']) ? $options[1]['option'] : '';
        $option3 = isset($options[2]['option']) ? $options[2]['option'] : '';
        $option4 = isset($options[3]['option']) ? $options[3]['option'] : '';
    }
}

// Delete
if(isset($_GET['delete'])){
    $id=intval($_GET['delete']);
    $conn->query("DELETE FROM assessment_questions WHERE id=$id");
    $success="Question deleted successfully!";
}
?>

<div class="main-content">
    <div class="mb-4">
        <h2 class="fw-bold"><i class="fas fa-clipboard-question me-2"></i><?= $editing ? 'Edit Assessment Question' : 'Manage Assessment Questions' ?></h2>
        <p class="text-muted"><?= $editing ? 'Update assessment question details' : 'Add and manage general assessment questions' ?></p>
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
    <!-- Add Form -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-warning text-white">
            <h5 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Add New Assessment Question</h5>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Question</label>
                        <input type="text" name="question" class="form-control" placeholder="Enter assessment question" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Option 1 (Required)</label>
                        <input type="text" name="option1" class="form-control" placeholder="First option" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Option 2 (Required)</label>
                        <input type="text" name="option2" class="form-control" placeholder="Second option" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Option 3 (Optional)</label>
                        <input type="text" name="option3" class="form-control" placeholder="Third option">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Option 4 (Optional)</label>
                        <input type="text" name="option4" class="form-control" placeholder="Fourth option">
                    </div>
                    <div class="col-12">
                        <button type="submit" name="add_assessment" class="btn btn-warning btn-lg">
                            <i class="fas fa-plus me-2"></i>Add Question
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php else: ?>
    <!-- Edit Form -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white">
            <h5 class="mb-0 fw-bold">Question Details</h5>
        </div>
        <div class="card-body">
            <form method="POST">
                <input type="hidden" name="id" value="<?= $question['id'] ?>">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Question</label>
                        <input type="text" name="question" class="form-control form-control-lg" value="<?=htmlspecialchars($question['question'])?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Option 1 (Required)</label>
                        <input type="text" name="option1" class="form-control" value="<?=htmlspecialchars($option1)?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Option 2 (Required)</label>
                        <input type="text" name="option2" class="form-control" value="<?=htmlspecialchars($option2)?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Option 3 (Optional)</label>
                        <input type="text" name="option3" class="form-control" value="<?=htmlspecialchars($option3)?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Option 4 (Optional)</label>
                        <input type="text" name="option4" class="form-control" value="<?=htmlspecialchars($option4)?>">
                    </div>
                    <div class="col-12">
                        <button type="submit" name="update_assessment" class="btn btn-primary btn-lg">
                            <i class="fas fa-save me-2"></i>Update Question
                        </button>
                        <a href="manage_assessments.php" class="btn btn-secondary btn-lg">
                            <i class="fas fa-arrow-left me-2"></i>Back to Questions
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>

    <!-- Table -->
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0 fw-bold"><i class="fas fa-list-check me-2"></i>All Assessment Questions</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th width="50">#</th>
                            <th>Question</th>
                            <th width="300">Options</th>
                            <th width="200" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $questions=$conn->query("SELECT * FROM assessment_questions ORDER BY id DESC");
                        if($questions->num_rows > 0){
                            $i=1;
                            while($row=$questions->fetch_assoc()){
                                echo "<tr>
                                    <td>{$i}</td>
                                    <td>
                                        <i class='fas fa-question-circle me-2 text-warning'></i>
                                        <strong>" . htmlspecialchars($row['question']) . "</strong>
                                    </td>
                                    <td><small>" . formatOptionsPreview($row['options']) . "</small></td>
                                    <td class='text-end'>
                                        <a href='manage_assessments.php?edit={$row['id']}' class='btn btn-sm btn-primary'>
                                            <i class='fas fa-edit'></i>
                                        </a>
                                        <a href='manage_assessments.php?delete={$row['id']}' class='btn btn-sm btn-danger' onclick='return confirm(\"Delete this question?\")'>  
                                            <i class='fas fa-trash'></i>
                                        </a>
                                    </td>
                                </tr>";
                                $i++;
                            }
                        } else {
                            echo "<tr><td colspan='4' class='text-center py-4'>
                                <i class='fas fa-inbox fa-3x text-muted mb-3 d-block'></i>
                                <p class='text-muted'>No assessment questions yet. Add your first question above!</p>
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