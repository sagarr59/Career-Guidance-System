<?php
session_start();
include 'header.php';
include 'db.php';
include 'algorithms/career-matching.php';

if(!isset($_SESSION['student_id'])){
    header("Location: login.php");
    exit;
}

// Initialize variables
$assessment_completed = false;
$personality_score = 0;
$skills_score = 0;
$traits = [];
$top_matches = [];

// Check if user has completed the assessment
if (isset($_SESSION['assessment_step1']) && 
    isset($_SESSION['assessment_step2']) && 
    isset($_SESSION['assessment_step3'])) {
    $assessment_completed = true;
    
    // Calculate scores from assessment steps
    // Calculate personality score from step 3 data
    if (isset($_SESSION['assessment_step3'])) {
        foreach ($_SESSION['assessment_step3'] as $key => $value) {
            if (strpos($key, 'personality_') === 0) {
                $personality_score += (int)$value;
            }
        }
        $_SESSION['personality_score'] = $personality_score;
    }

    // Calculate skills score from step 1 data
    if (isset($_SESSION['assessment_step1']['skills'])) {
        $skills_score = count($_SESSION['assessment_step1']['skills']) * 2; // 2 points per skill
        $_SESSION['skills_score'] = $skills_score;
    }

    // Calculate traits from personality answers
    if (isset($_SESSION['assessment_step3'])) {
        // Enhanced trait calculation based on specific question patterns
        $analytical_score = 0;
        $leadership_score = 0;
        $social_score = 0;
        $practical_score = 0;
        
        // Analyze specific personality question responses
        foreach ($_SESSION['assessment_step3'] as $key => $value) {
            if (strpos($key, 'personality_') === 0) {
                $question_id = str_replace('personality_', '', $key);
                $response_value = (int)$value;
                
                // Map responses to trait scores based on question content
                switch($question_id) {
                    case '1': // What do you enjoy doing in your free time?
                        if ($response_value == 1) $analytical_score += 3; // Reading books or studying
                        elseif ($response_value == 4) $social_score += 3; // Helping family or friends
                        elseif ($response_value == 3) $practical_score += 2; // Drawing or creative activities
                        break;
                    case '2': // Which subject do you like most in school?
                        if ($response_value == 1) $analytical_score += 4; // Mathematics or Science
                        elseif ($response_value == 2) $practical_score += 3; // Literature or Arts
                        elseif ($response_value == 4) $analytical_score += 2; // Computer or Technology
                        break;
                    case '3': // When working on a project, you:
                        if ($response_value == 1) $analytical_score += 3; // Plan everything first
                        elseif ($response_value == 3) $social_score += 3; // Ask for help from others
                        elseif ($response_value == 4) $practical_score += 3; // Try different approaches
                        elseif ($response_value == 2) $leadership_score += 2; // Start immediately and adjust
                        break;
                    case '4': // Your biggest strength is:
                        if ($response_value == 1) $analytical_score += 4; // Being good at calculations
                        elseif ($response_value == 2) $practical_score += 3; // Being creative and artistic
                        elseif ($response_value == 3) $social_score += 4; // Being a good listener
                        elseif ($response_value == 4) $leadership_score += 3; // Being confident and bold
                        break;
                    case '5': // After SEE/+2, you want to:
                        if ($response_value == 1) $analytical_score += 2; // Continue higher education
                        elseif ($response_value == 3) $practical_score += 3; // Learn a skill or trade
                        elseif ($response_value == 4) $leadership_score += 4; // Start a small business
                        break;
                    case '6': // Which activity excites you most?
                        if ($response_value == 1) $analytical_score += 4; // Solving puzzles or brain teasers
                        elseif ($response_value == 2) $practical_score += 3; // Performing or acting
                        elseif ($response_value == 3) $social_score += 4; // Volunteering for social causes
                        elseif ($response_value == 4) $practical_score += 3; // Building or fixing things
                        break;
                }
            }
        }
        
        // Normalize trait scores to 0-10 scale
        $traits = [
            'Analytical' => min(10, round($analytical_score * 0.7)),
            'Leadership' => min(10, round($leadership_score * 0.8)),
            'Social' => min(10, round($social_score * 0.7)),
            'Practical' => min(10, round($practical_score * 0.7))
        ];
        $_SESSION['personality_traits'] = json_encode($traits);
    }

    // Get all careers from database
    $result = $conn->query("SELECT * FROM careers");
    $careers = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $careers[] = $row;
        }
    }

    // Calculate suitability scores for each career
    $career_matches = [];
    $selected_skills = isset($_SESSION['assessment_step1']['skills']) ? $_SESSION['assessment_step1']['skills'] : [];

    foreach($careers as $career) {
        $score = careerRecommend($personality_score, $skills_score, $career, $traits, $selected_skills);
        $career_matches[] = [
            'career' => $career,
            'score' => $score
        ];
    }

    // Sort by score (highest first)
    usort($career_matches, function($a, $b) {
        return $b['score'] <=> $a['score'];
    });

    // Show only top 3 matches
    $top_matches = array_slice($career_matches, 0, 3);
}

// Custom styles for career cards
echo '<style>
.career-card {
    transition: all 0.3s ease;
    border: none;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    border-radius: 12px;
    overflow: hidden;
}

.career-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

.career-card-header {
    background: linear-gradient(135deg, #3498db 0%, #2c3e50 100%);
    color: white;
    padding: 20px;
    position: relative;
}

.rank-badge {
    position: absolute;
    top: 15px;
    right: 15px;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 1.2rem;
}

.rank-1 { background: #FFD700; color: #333; }
.rank-2 { background: #C0C0C0; color: #333; }
.rank-3 { background: #CD7F32; color: #fff; }

.career-title {
    margin: 0;
    font-size: 1.5rem;
    font-weight: 600;
}

.match-score {
    text-align: center;
    padding: 15px;
    background: #f8f9fa;
    border-top: 1px solid #eee;
}

.score-value {
    font-weight: bold;
    color: #3498db;
    font-size: 1.2rem;
}

.btn-primary {
    background: #3498db;
    border: none;
    border-radius: 8px;
    padding: 10px 15px;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    background: #2980b9;
    transform: translateY(-2px);
}
</style>';
?>

<div class="container py-5">
    <div class="row">
        <div class="col-12 text-center mb-5">
            <h1 class="display-5 fw-bold"><i class="fas fa-chart-line me-2"></i>Your Career Insights</h1>
            <p class="lead">Based on your assessment, here are personalized career recommendations</p>
        </div>
    </div>
    
    <?php if($assessment_completed && !empty($top_matches)): ?>
    <div class="row mb-5">
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="fas fa-user-circle fa-2x text-primary mb-3"></i>
                    <h5>Personality Score</h5>
                    <h3 class="text-primary fw-bold"><?= round(($personality_score / 24) * 100) ?>/100</h3>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="fas fa-lightbulb fa-2x text-success mb-3"></i>
                    <h5>Skills Score</h5>
                    <h3 class="text-success fw-bold"><?= round(($skills_score / 30) * 100) ?>/100</h3>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mb-5">
        <div class="col-12">
            <div class="text-center mb-5">
                <h3 class="fw-bold mb-3"><i class="fas fa-user-alt me-2"></i>Your Personality Profile</h3>
                <p class="text-muted">Discover your unique personality traits and how they align with career opportunities</p>
            </div>
            <div class="row">
                <?php foreach($traits as $trait_name => $trait_value): ?>
                <div class="col-lg-3 col-md-6 col-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="fas fa-<?php 
                                    switch($trait_name) {
                                        case 'Analytical': echo 'brain'; break;
                                        case 'Leadership': echo 'user-friends'; break;
                                        case 'Social': echo 'comments'; break;
                                        case 'Practical': echo 'tools'; break;
                                        default: echo 'star';
                                    }
                                ?> fa-2x mb-3"></i>
                                <h5><?= $trait_name ?></h5>
                            </div>
                            <div class="mt-auto">
                                <div class="progress">
                                    <div class="progress-bar bg-primary" role="progressbar" 
                                         style="width: <?= $trait_value * 10 ?>%" 
                                         aria-valuenow="<?= $trait_value ?>" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                        <?= $trait_value ?>/10
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    
    <div class="row mb-5">
        <div class="col-12">
            <div class="text-center mb-5">
                <h3 class="fw-bold mb-3"><i class="fas fa-briefcase me-2"></i>Top Career Matches</h3>
                <p class="text-muted">Personalized career recommendations based on your profile</p>
            </div>
            
            <div class="row">
                <?php foreach($top_matches as $index => $match): ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card career-card h-100">
                        <div class="career-card-header">
                            <div class="rank-badge rank-<?= $index + 1 ?>">
                                #<?= $index + 1 ?>
                            </div>
                            <h4 class="career-title"><?= htmlspecialchars($match['career']['career_name']) ?></h4>
                        </div>
                        <div class="card-body">
                            <div class="career-details">
                                <div class="mb-3">
                                    <strong><i class="fas fa-graduation-cap me-2"></i>Education Required:</strong>
                                    <p><?= isset($match['career']['education_required']) ? htmlspecialchars($match['career']['education_required']) : 'Information not available' ?></p>
                                </div>
                                
                                <div class="mb-3">
                                    <strong><i class="fas fa-file-alt me-2"></i>Description:</strong>
                                    <p><?= htmlspecialchars(substr($match['career']['description'], 0, 100)) ?>...</p>
                                </div>
                                
                                <div class="mb-3">
                                    <strong><i class="fas fa-money-bill-wave me-2"></i>Salary Range:</strong>
                                    <p><?= htmlspecialchars($match['career']['salary_range']) ?></p>
                                </div>
                                
                            </div>
                            
                            <?php if(isset($_SESSION['student_id'])): ?>
                            <div class="mt-3">
                                <a href="career_details.php?id=<?= $match['career']['id'] ?>" class="btn btn-primary w-100">
                                    <i class="fas fa-info-circle me-2"></i>View Details
                                </a>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="card-footer">
                            <div class="match-score">
                                Match Score: <span class="score-value"><?= round($match['score']) ?>%</span>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    
    <div class="text-center mt-5">
        <a href="userprofile.php" class="btn btn-success btn-lg">
            <i class="fas fa-user-circle me-2"></i>View Full Profile
        </a>
        <a href="career_path_generator.php" class="btn btn-primary btn-lg ms-3">
            <i class="fas fa-project-diagram me-2"></i>Generate Career Path
        </a>
    </div>
    <?php else: ?>
    <div class="row">
        <div class="col-12">
            <div class="text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-file-alt fa-4x text-muted"></i>
                </div>
                <h3 class="fw-bold mb-3">Assessment Not Completed</h3>
                <p class="text-muted mb-4">You need to complete the career assessment to see your personalized recommendations.</p>
                <a href="assessment.php" class="btn btn-primary btn-lg">
                    <i class="fas fa-clipboard-list me-2"></i>Take Assessment Now
                </a>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
// Animate progress bars
document.addEventListener('DOMContentLoaded', function() {
    const progressBars = document.querySelectorAll('.progress-bar');
    progressBars.forEach(bar => {
        const width = bar.style.width;
        bar.style.width = '0';
        setTimeout(() => {
            bar.style.width = width;
        }, 300);
    });
});
</script>

<?php include 'footer.php'; ?>