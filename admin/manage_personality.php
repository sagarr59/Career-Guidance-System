<?php
include 'header.php';
if(!isset($_SESSION['admin_logged_in'])){ 
    header("Location: login.php"); 
    exit; 
}

include '../db.php';
include 'sidebar.php';

// Check if we're editing a personality question
$editing = false;
$personality = null;
if(isset($_GET['edit'])){
    $editing = true;
    $id = intval($_GET['edit']);
    $personality = $conn->query("SELECT * FROM personality_questions WHERE id=$id")->fetch_assoc();
    if(!$personality) header("Location: manage_personality.php");
}

// Handle Add Personality Question
if(isset($_POST['add_personality'])){
    $question = trim($_POST['question']);
    $option1 = trim($_POST['option1']);
    $option2 = trim($_POST['option2']);
    $option3 = trim($_POST['option3']);
    $option4 = trim($_POST['option4']);
    $score   = intval($_POST['score']);
    $trait   = trim($_POST['trait']);

    if(empty($question) || empty($option1) || empty($option2)){
        $error = "Question and first two options are required.";
    } else {
        $stmt = $conn->prepare("INSERT INTO personality_questions (question, option1, option2, option3, option4, score, trait) VALUES (?,?,?,?,?,?,?)");
        $stmt->bind_param("sssssis",$question,$option1,$option2,$option3,$option4,$score,$trait);
        $stmt->execute();
        $success = "Personality question added successfully!";
    }
}

// Handle Update Personality Question
if(isset($_POST['update_personality'])){
    $id = intval($_POST['id']);
    $q = trim($_POST['question']);
    $opt1 = trim($_POST['option1']);
    $opt2 = trim($_POST['option2']);
    $opt3 = trim($_POST['option3']);
    $opt4 = trim($_POST['option4']);
    $score = intval($_POST['score']);
    $trait = trim($_POST['trait']);

    if(empty($q) || empty($opt1) || empty($opt2)){
        $error = "Question and first two options are required.";
    } else {
        $stmt = $conn->prepare("UPDATE personality_questions SET question=?, option1=?, option2=?, option3=?, option4=?, score=?, trait=? WHERE id=?");
        $stmt->bind_param("ssssiiis",$q,$opt1,$opt2,$opt3,$opt4,$score,$trait,$id);
        $stmt->execute();
        $success = "Question updated successfully!";
        
        // Refresh personality data
        $personality = $conn->query("SELECT * FROM personality_questions WHERE id=$id")->fetch_assoc();
    }
}

// Handle Delete
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM personality_questions WHERE id=$id");
    $success = "Question deleted successfully!";
}
?>

<div class="main-content">
    <div class="mb-4">
        <h2 class="fw-bold"><i class="fas fa-brain me-2"></i><?= $editing ? 'Edit Personality Question' : 'Manage Personality Questions' ?></h2>
        <p class="text-muted"><?= $editing ? 'Update personality question details' : 'Add and manage personality assessment questions' ?></p>
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
    <!-- Add Question Form -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Add New Personality Question</h5>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Question</label>
                        <input type="text" name="question" class="form-control" placeholder="Enter personality question" required>
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
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Score</label>
                        <input type="number" name="score" class="form-control" value="3" min="1" max="10" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Personality Trait</label>
                        <select name="trait" class="form-select" required>
                            <option value="Analytical">Analytical</option>
                            <option value="Creative">Creative</option>
                            <option value="Social">Social</option>
                            <option value="Leadership">Leadership</option>
                            <option value="Technical">Technical</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <button type="submit" name="add_personality" class="btn btn-info btn-lg">
                            <i class="fas fa-plus me-2"></i>Add Question
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php else: ?>
    <!-- Edit Question Form -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white">
            <h5 class="mb-0 fw-bold">Question Details</h5>
        </div>
        <div class="card-body">
            <form method="POST">
                <input type="hidden" name="id" value="<?= $personality['id'] ?>">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Question</label>
                        <input type="text" name="question" class="form-control form-control-lg" value="<?=htmlspecialchars($personality['question'])?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Option 1 (Required)</label>
                        <input type="text" name="option1" class="form-control" value="<?=htmlspecialchars($personality['option1'])?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Option 2 (Required)</label>
                        <input type="text" name="option2" class="form-control" value="<?=htmlspecialchars($personality['option2'])?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Option 3 (Optional)</label>
                        <input type="text" name="option3" class="form-control" value="<?=htmlspecialchars($personality['option3'])?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Option 4 (Optional)</label>
                        <input type="text" name="option4" class="form-control" value="<?=htmlspecialchars($personality['option4'])?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Score</label>
                        <input type="number" name="score" class="form-control" value="<?=htmlspecialchars($personality['score'])?>" min="1" max="10" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Personality Trait</label>
                        <select name="trait" class="form-select" required>
                            <option value="Analytical" <?= $personality['trait'] == 'Analytical' ? 'selected' : '' ?>>Analytical</option>
                            <option value="Creative" <?= $personality['trait'] == 'Creative' ? 'selected' : '' ?>>Creative</option>
                            <option value="Social" <?= $personality['trait'] == 'Social' ? 'selected' : '' ?>>Social</option>
                            <option value="Leadership" <?= $personality['trait'] == 'Leadership' ? 'selected' : '' ?>>Leadership</option>
                            <option value="Technical" <?= $personality['trait'] == 'Technical' ? 'selected' : '' ?>>Technical</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <button type="submit" name="update_personality" class="btn btn-primary btn-lg">
                            <i class="fas fa-save me-2"></i>Update Question
                        </button>
                        <a href="manage_personality.php" class="btn btn-secondary btn-lg">
                            <i class="fas fa-arrow-left me-2"></i>Back to Questions
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>

    <!-- Personality Questions Table -->
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0 fw-bold"><i class="fas fa-list-check me-2"></i>All Personality Questions</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th width="50">#</th>
                            <th>Question</th>
                            <th width="300">Options</th>
                            <th width="80">Score</th>
                            <th width="200" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $questions = $conn->query("SELECT * FROM personality_questions ORDER BY id DESC");
                        if($questions->num_rows > 0){
                            $i = 1;
                            while($row = $questions->fetch_assoc()){
                                echo '<tr>
                                    <td>'.$i.'</td>
                                    <td>
                                        <i class="fas fa-question-circle me-2 text-info"></i>
                                        <strong>' . htmlspecialchars($row['question']) . '</strong>
                                    </td>
                                    <td><small>' . htmlspecialchars($row['option1']) . ', ' . htmlspecialchars($row['option2']) . '</small></td>
                                    <td><span class="badge bg-info">'.$row['score'].'</span></td>
                                    <td class="text-end">
                                        <a href="manage_personality.php?edit='.$row['id'].'" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="manage_personality.php?delete='.$row['id'].'" class="btn btn-sm btn-danger" onclick="return confirm(\'Delete this question?\')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>';
                                $i++;
                            }
                        } else {
                            echo "<tr><td colspan='5' class='text-center py-4'>
                                <i class='fas fa-inbox fa-3x text-muted mb-3 d-block'></i>
                                <p class='text-muted'>No personality questions yet. Add your first question above!</p>
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
