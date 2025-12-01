<?php
// Start session and include database connection
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'db.php';

// Check if student is logged in
if(!isset($_SESSION['student_id'])){
    header("Location: login.php");
    exit;
}

// Handle retake assessment
if (isset($_GET['retake']) && $_GET['retake'] == 'true') {
    // Clear all assessment data from session
    unset($_SESSION['assessment_step1']);
    unset($_SESSION['assessment_step2']);
    unset($_SESSION['assessment_step3']);
    unset($_SESSION['assessment_draft_step1']);
    unset($_SESSION['assessment_draft_step2']);
    unset($_SESSION['assessment_draft_step3']);
    unset($_SESSION['personality_score']);
    unset($_SESSION['skills_score']);
    
    // Reset to step 1
    header("Location: assessment.php?step=1");
    exit;
}



// Handle form submissions BEFORE including header
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Save draft functionality
    if (isset($_POST['save_draft'])) {
        // Determine current step
        $step = isset($_GET['step']) ? (int)$_GET['step'] : 1;
        
        // Save current step data to session
        if ($step == 1) {
            $_SESSION['assessment_draft_step1'] = [
                'skills' => $_POST['skills'] ?? []
            ];
        } elseif ($step == 2) {
            // Save assessment answers
            $assessment_answers = [];
            foreach ($_POST as $key => $value) {
                if (strpos($key, 'assessment_') === 0) {
                    $assessment_answers[$key] = $value;
                }
            }
            $_SESSION['assessment_draft_step2'] = $assessment_answers;
        } elseif ($step == 3) {
            // Save personality answers
            $personality_answers = [];
            foreach ($_POST as $key => $value) {
                if (strpos($key, 'personality_') === 0) {
                    $personality_answers[$key] = $value;
                }
            }
            $_SESSION['assessment_draft_step3'] = $personality_answers;
        }
        
        // Set draft saved message
        $_SESSION['draft_saved'] = true;
        
        // Redirect to next step or stay on current
        if (isset($_POST['next'])) {
            header("Location: assessment.php?step=" . ($step + 1));
        } else {
            header("Location: assessment.php?step=" . $step);
        }
        exit;
    }
    
    // Handle next/submit
    if (isset($_POST['next']) || isset($_POST['submit'])) {
        // Determine current step
        $step = isset($_GET['step']) ? (int)$_GET['step'] : 1;
        
        // Save current step data
        if ($step == 1) {
            $_SESSION['assessment_step1'] = [
                'skills' => $_POST['skills'] ?? []
            ];
            
            // Clear draft for this step
            unset($_SESSION['assessment_draft_step1']);
        } elseif ($step == 2) {
            // Save assessment answers
            $assessment_answers = [];
            foreach ($_POST as $key => $value) {
                if (strpos($key, 'assessment_') === 0) {
                    $assessment_answers[$key] = $value;
                }
            }
            $_SESSION['assessment_step2'] = $assessment_answers;
            
            // Clear draft for this step
            unset($_SESSION['assessment_draft_step2']);
        } elseif ($step == 3) {
            // Save personality answers
            $personality_answers = [];
            foreach ($_POST as $key => $value) {
                if (strpos($key, 'personality_') === 0) {
                    $personality_answers[$key] = $value;
                }
            }
            $_SESSION['assessment_step3'] = $personality_answers;
            
            // Clear draft for this step
            unset($_SESSION['assessment_draft_step3']);
            
            // If submitting, redirect to results
            if (isset($_POST['submit'])) {
                header("Location: results.php");
                exit;
            }
        }
        
        // Move to next step
        if (isset($_POST['next'])) {
            header("Location: assessment.php?step=" . ($step + 1));
            exit;
        }
    }
}

// Determine current step
$step = isset($_GET['step']) ? (int)$_GET['step'] : 1;

// Now include header after processing all redirects
include 'header.php';

// Load draft data if exists
$draft_data = [];
if ($step == 1 && isset($_SESSION['assessment_draft_step1'])) {
    $draft_data = $_SESSION['assessment_draft_step1'];
} elseif ($step == 2 && isset($_SESSION['assessment_draft_step2'])) {
    $draft_data = $_SESSION['assessment_draft_step2'];
} elseif ($step == 3 && isset($_SESSION['assessment_draft_step3'])) {
    $draft_data = $_SESSION['assessment_draft_step3'];
}

// Fetch data for current step with randomization
if ($step == 1) {
    // Fetch Skills from DB (limited to first 15 for better UX)
    $skills = $conn->query("SELECT * FROM skills ORDER BY RAND() LIMIT 15");
} elseif ($step == 2) {
    // Fetch Assessment Questions (limited for better UX)
    $assessments = $conn->query("SELECT * FROM assessment_questions ORDER BY RAND() LIMIT 5");
} elseif ($step == 3) {
    // Fetch Personality Questions (limited for better UX)
    $personality = $conn->query("SELECT * FROM personality_questions ORDER BY RAND() LIMIT 6");
}

// Check if draft saved message should be shown
$draft_saved = isset($_SESSION['draft_saved']) ? $_SESSION['draft_saved'] : false;
if ($draft_saved) {
    unset($_SESSION['draft_saved']);
}
?>

<style>
.interest-card {
    cursor: pointer;
    transition: all 0.3s ease;
    border: 2px solid #e9ecef;
}
.interest-card:hover {
    transform: translateY(-5px);
    border-color: #3498db;
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
}
.interest-card input:checked + div {
    background: #3498db;
    color: white;
    border-color: #3498db;
}
.interest-card input:checked + div .interest-icon {
    color: white;
}
.interest-card input:checked + div .check-mark {
    opacity: 1;
}
.interest-icon {
    font-size: 2rem;
    color: #3498db;
    margin-bottom: 15px;
}
.interest-title {
    font-weight: 600;
    margin-bottom: 5px;
}
.interest-desc {
    font-size: 0.85rem;
    opacity: 0.8;
}
.check-mark {
    position: absolute;
    top: 10px;
    right: 10px;
    color: #27ae60;
    opacity: 0;
    transition: opacity 0.3s ease;
}
.step-indicator {
    transition: all 0.3s ease;
}
.step-indicator.active {
    background: #3498db !important;
    color: white !important;
    transform: scale(1.1);
}
.step-line {
    transition: background 0.3s ease;
}
.alert-success {
    animation: fadeIn 0.5s ease-in-out;
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>

<section class="py-5" style="min-height: 80vh; background: #f8f9fa;">
    <div class="container">
        <div class="text-center mb-5">
            <div style="display: inline-block; padding: 20px; background: #2c3e50; border-radius: 50%; box-shadow: 0 4px 15px rgba(0,0,0,0.2); margin-bottom: 20px;">
                <i class="fas fa-clipboard-list fa-2x text-white"></i>
            </div>
            <h2 class="fw-bold mb-3" style="color: #2c3e50;">Career Assessment</h2>
            <p class="lead text-muted">Complete this assessment to get personalized career recommendations</p>
            
            <!-- Step indicator -->
            <div class="mt-4">
                <div class="d-flex justify-content-center">
                    <div class="step-indicator mx-2 <?= $step == 1 ? 'active' : '' ?>" style="width: 30px; height: 30px; border-radius: 50%; background: <?= $step == 1 ? '#3498db' : '#ecf0f1' ?>; color: <?= $step == 1 ? 'white' : '#7f8c8d' ?>; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                        1
                    </div>
                    <div class="step-line mx-2" style="width: 50px; height: 4px; background: #ecf0f1; margin-top: 13px;"></div>
                    <div class="step-indicator mx-2 <?= $step == 2 ? 'active' : '' ?>" style="width: 30px; height: 30px; border-radius: 50%; background: <?= $step == 2 ? '#3498db' : '#ecf0f1' ?>; color: <?= $step == 2 ? 'white' : '#7f8c8d' ?>; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                        2
                    </div>
                    <div class="step-line mx-2" style="width: 50px; height: 4px; background: #ecf0f1; margin-top: 13px;"></div>
                    <div class="step-indicator mx-2 <?= $step == 3 ? 'active' : '' ?>" style="width: 30px; height: 30px; border-radius: 50%; background: <?= $step == 3 ? '#3498db' : '#ecf0f1' ?>; color: <?= $step == 3 ? 'white' : '#7f8c8d' ?>; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                        3
                    </div>
                </div>
                <div class="d-flex justify-content-between mt-2" style="max-width: 200px; margin: 0 auto;">
                    <small class="text-muted">Skills</small>
                    <small class="text-muted">Assessment</small>
                    <small class="text-muted">Personality</small>
                </div>
            </div>
            
            <?php if($draft_saved): ?>
                <div class="alert alert-success mt-3" style="max-width: 600px; margin: 15px auto 0; border-radius: 10px;">
                    <i class="fas fa-check-circle me-2"></i>Draft saved successfully!
                </div>
            <?php endif; ?>
        </div>

        <form method="post">
            <?php if($step == 1): ?>
                <!-- Step 1: Skills Selection -->
                <div data-aos="fade-up" class="mb-5">
                    <div class="text-center mb-4">
                        <h4 class="fw-semibold mb-2">Step 1: Select Your Skills</h4>
                        <p class="text-muted">Choose at least 3 skills you currently have or want to develop</p>
                    </div>
                    <div class="row">
                        <?php 
                        // Reset the result set
                        $skills->data_seek(0);
                        while($skill = $skills->fetch_assoc()): 
                            // Check if this skill was previously selected in draft
                            $is_checked = isset($draft_data['skills']) && in_array($skill['id'], $draft_data['skills']);
                        ?>
                            <div class="col-md-4 mb-3">
                                <div class="form-check card p-3 shadow-sm h-100" style="border-radius: 12px; transition: all 0.3s ease; cursor: pointer;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                                    <input class="form-check-input" type="checkbox" name="skills[]" value="<?php echo $skill['id']; ?>" id="skill<?php echo $skill['id']; ?>" <?= $is_checked ? 'checked' : '' ?>>
                                    <label class="form-check-label fw-semibold" for="skill<?php echo $skill['id']; ?>" style="color: #2c3e50;">
                                        <i class="fas fa-star me-2" style="color: #f39c12;"></i><?php echo $skill['skill_name']; ?>
                                    </label>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            
            <?php elseif($step == 2): ?>
                <!-- Step 2: Assessment Questions -->
                <div data-aos="fade-up" class="mb-5">
                    <div class="text-center mb-4">
                        <h4 class="fw-semibold mb-2">Step 2: Assessment Questions</h4>
                        <p class="text-muted">Answer honestly to get the best career recommendations</p>
                    </div>
                    <?php 
                    // Reset the result set
                    $assessments->data_seek(0);
                    while($q = $assessments->fetch_assoc()): 
                        // Check if this question was previously answered in draft
                        $selected_value = isset($draft_data["assessment_{$q['id']}"]) ? $draft_data["assessment_{$q['id']}"] : '';
                        // Decode options from JSON
                        $options = json_decode($q['options'], true);
                    ?>
                        <div class="card p-4 mb-4 shadow-sm" style="border-radius: 12px; border-left: 5px solid #3498db; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='translateY(0)';">
                            <div class="d-flex align-items-center mb-3">
                                <div style="width: 40px; height: 40px; background: #3498db; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; margin-right: 15px;">
                                    ?
                                </div>
                                <h5 class="fw-semibold mb-0" style="color: #2c3e50;"><?php echo $q['question']; ?></h5>
                            </div>
                            <div class="row mt-3">
                                <?php foreach($options as $index => $option): ?>
                                <div class="col-md-6 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="assessment_<?php echo $q['id']; ?>" value="<?php echo $option['weight']; ?>" id="assess_<?php echo $q['id']; ?>_<?php echo $index+1; ?>" <?= $selected_value == $option['weight'] ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="assess_<?php echo $q['id']; ?>_<?php echo $index+1; ?>"><?php echo $option['option']; ?></label>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            
            <?php elseif($step == 3): ?>
                <!-- Step 3: Personality Questions -->
                <div data-aos="fade-up" class="mb-5">
                    <div class="text-center mb-4">
                        <h4 class="fw-semibold mb-2">Step 3: Personality Questions</h4>
                        <p class="text-muted">Tell us about your preferences and interests</p>
                    </div>
                    <?php 
                    // Reset the result set
                    $personality->data_seek(0);
                    while($p = $personality->fetch_assoc()): 
                        // Check if this question was previously answered in draft
                        $selected_value = isset($draft_data["personality_{$p['id']}"]) ? $draft_data["personality_{$p['id']}"] : '';
                    ?>
                        <div class="card p-4 mb-4 shadow-sm" style="border-radius: 12px; border-left: 5px solid #27ae60; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='translateY(0)';">
                            <div class="d-flex align-items-center mb-3">
                                <div style="width: 40px; height: 40px; background: #27ae60; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; margin-right: 15px;">
                                    ?
                                </div>
                                <h5 class="fw-semibold mb-0" style="color: #2c3e50;"><?php echo $p['question']; ?></h5>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="personality_<?php echo $p['id']; ?>" value="1" id="pers_<?php echo $p['id']; ?>_1" <?= $selected_value == '1' ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="pers_<?php echo $p['id']; ?>_1"><?php echo $p['option1']; ?></label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="personality_<?php echo $p['id']; ?>" value="2" id="pers_<?php echo $p['id']; ?>_2" <?= $selected_value == '2' ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="pers_<?php echo $p['id']; ?>_2"><?php echo $p['option2']; ?></label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="personality_<?php echo $p['id']; ?>" value="3" id="pers_<?php echo $p['id']; ?>_3" <?= $selected_value == '3' ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="pers_<?php echo $p['id']; ?>_3"><?php echo $p['option3']; ?></label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="personality_<?php echo $p['id']; ?>" value="4" id="pers_<?php echo $p['id']; ?>_4" <?= $selected_value == '4' ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="pers_<?php echo $p['id']; ?>_4"><?php echo $p['option4']; ?></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php endif; ?>

            <div class="text-center mt-5">
                <?php if($step > 1): ?>
                    <a href="assessment.php?step=<?= $step - 1 ?>" class="btn btn-secondary btn-lg px-4 py-2 me-2" style="border-radius: 10px;">
                        <i class="fas fa-arrow-left me-2"></i>Previous
                    </a>
                <?php endif; ?>
                
                <button type="submit" name="save_draft" class="btn btn-outline-primary btn-lg px-4 py-2 me-2" style="border-radius: 10px;">
                    <i class="fas fa-save me-2"></i>Save Draft
                </button>
                
                <?php if($step < 3): ?>
                    <button type="submit" name="next" class="btn btn-primary btn-lg px-4 py-2" style="border-radius: 10px;">
                        Next<i class="fas fa-arrow-right ms-2"></i>
                    </button>
                <?php else: ?>
                    <button type="submit" name="submit" class="btn btn-success btn-lg px-4 py-2" style="border-radius: 10px;">
                        <i class="fas fa-rocket me-2"></i>Get My Career Recommendations
                    </button>
                <?php endif; ?>
                
                <p class="text-muted mt-3"><small><i class="fas fa-info-circle"></i> This will take approximately 5-10 minutes to complete</small></p>
            </div>
        </form>
    </div>
</section>

<!-- Frontend Validation -->
<script>
// Add hover effects to cards
const cards = document.querySelectorAll('.card');
cards.forEach(card => {
    card.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-5px)';
    });
    card.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0)';
    });
});

// Add hover effects to skill cards
const skillCards = document.querySelectorAll('.form-check.card');
skillCards.forEach(card => {
    card.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-5px)';
    });
    card.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0)';
    });
});

// Form validation for current step
const form = document.querySelector('form');
if (form) {
    form.addEventListener('submit', function(e) {
        const step = <?= $step ?>;
        
        if (step === 1) {
            // Check at least 3 skills selected
            const selectedSkills = document.querySelectorAll('input[name="skills[]"]:checked');
            if (selectedSkills.length < 3) {
                e.preventDefault();
                alert("Please select at least 3 skills that you have or want to develop.");
                return false;
            }
        } else if (step === 2) {
            // Check all assessment questions answered
            const assessmentQuestions = document.querySelectorAll('[name^="assessment_"]');
            const assessmentIds = new Set();
            
            assessmentQuestions.forEach(q => {
                const id = q.name.match(/assessment_(\d+)/)[1];
                assessmentIds.add(id);
            });
            
            for (let id of assessmentIds) {
                const name = 'assessment_' + id;
                const selected = document.querySelector(`input[name="${name}"]:checked`);
                if (!selected) {
                    e.preventDefault();
                    alert("Please answer all assessment questions.");
                    return false;
                }
            }
        } else if (step === 3) {
            // Check all personality questions answered
            const personalityQuestions = document.querySelectorAll('[name^="personality_"]');
            const personalityIds = new Set();
            
            personalityQuestions.forEach(q => {
                const id = q.name.match(/personality_(\d+)/)[1];
                personalityIds.add(id);
            });
            
            for (let id of personalityIds) {
                const name = 'personality_' + id;
                const selected = document.querySelector(`input[name="${name}"]:checked`);
                if (!selected) {
                    e.preventDefault();
                    alert("Please answer all personality questions.");
                    return false;
                }
            }
        }
    });
}
</script>

<?php include 'footer.php'; ?>