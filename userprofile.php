<?php
// Start session and include database connection
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'db.php';
include 'algorithms/career-matching.php';

// Check if student is logged in
if(!isset($_SESSION['student_id'])){
    header("Location: login.php");
    exit;
}

$student_id = $_SESSION['student_id'];
$student_name = $_SESSION['student_name'];
$student_email = $_SESSION['student_email'];

// Fetch student details from database
$stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

// Check if assessments completed
$personality_completed = isset($_SESSION['personality_score']) ? true : false;
$skills_completed = isset($_SESSION['skills_score']) ? true : false;

// Get career matches if both assessments completed
$top_careers = [];
if($personality_completed && $skills_completed) {
    // Fetch all careers
    $careers_query = $conn->query("SELECT * FROM careers ORDER BY id ASC");
    $all_careers = [];
    while($c = $careers_query->fetch_assoc()){
        $all_careers[] = $c;
    }
    
    // Calculate match scores using the same algorithm as results.php
    $personality_score = $_SESSION['personality_score'] ?? 0;
    $skills_score = $_SESSION['skills_score'] ?? 0;
    
    // Get traits from session
    $traits = isset($_SESSION['personality_traits']) ? json_decode($_SESSION['personality_traits'], true) : [];
    
    // Get selected skills from session
    $selected_skills = isset($_SESSION['assessment_step1']['skills']) ? $_SESSION['assessment_step1']['skills'] : [];
    
    $career_matches = [];
    foreach($all_careers as $career){
        // Use the same careerRecommend function as results.php
        $score = careerRecommend($personality_score, $skills_score, $career, $traits, $selected_skills);
        
        $career_matches[] = [
            'career' => $career,
            'score' => $score
        ];
    }
    
    // Sort by match score (highest first)
    usort($career_matches, function($a, $b) {
        return $b['score'] <=> $a['score'];
    });
    
    $top_careers = array_slice($career_matches, 0, 3);
}

// Educational Pathway Algorithm
function getEducationalPathways($top_careers) {
    $pathways = [];
    
    foreach($top_careers as $match) {
        $career = $match['career'];
        
        // Nepal-specific educational pathways
        $nepal_pathways = [
            'Software Developer' => [
                'stream' => '+2 Science (Computer)',
                'bachelor' => 'BSc Computer Science or BIT',
                'master' => 'MSc Computer Science or MBA (IT)',
                'institutions' => ['TU', 'PU', 'KU', 'VU'],
                'duration' => '6-8 years'
            ],
            'Data Scientist' => [
                'stream' => '+2 Science (Mathematics)',
                'bachelor' => 'BSc Statistics or BCA',
                'master' => 'MSc Statistics or MCA',
                'institutions' => ['TU', 'PU', 'KU'],
                'duration' => '6-7 years'
            ],
            'Teacher' => [
                'stream' => '+2 Any Stream',
                'bachelor' => 'BEd or Bachelor + BEd',
                'master' => 'MEd or MA + BEd',
                'institutions' => ['TU', 'PU', 'KU', 'Education Universities'],
                'duration' => '5-7 years'
            ],
            'Business Analyst' => [
                'stream' => '+2 Management',
                'bachelor' => 'BBS or BBA',
                'master' => 'MBS or MBA',
                'institutions' => ['TU', 'PU', 'KU', 'Himalayan University'],
                'duration' => '5-7 years'
            ],
            'Graphic Designer' => [
                'stream' => '+2 Any Stream',
                'bachelor' => 'BFA or BDes',
                'master' => 'MFA or MDes',
                'institutions' => ['Tribhuvan University', 'Private Colleges'],
                'duration' => '4-6 years'
            ],
            'Project Manager' => [
                'stream' => '+2 Any Stream',
                'bachelor' => 'BBS or BBA',
                'master' => 'MBS or MBA',
                'institutions' => ['TU', 'PU', 'KU', 'Himalayan University'],
                'duration' => '5-7 years'
            ],
            'Civil Engineer' => [
                'stream' => '+2 Science',
                'bachelor' => 'BE Civil Engineering',
                'master' => 'ME Civil Engineering or MBA',
                'institutions' => ['IOE/TU', 'PU', 'KU'],
                'duration' => '6-8 years'
            ],
            'Doctor' => [
                'stream' => '+2 Science',
                'bachelor' => 'MBBS',
                'master' => 'MD or MS in specialization',
                'institutions' => ['BPKIHS', 'KUSMS', 'Manipal'],
                'duration' => '5-7 years'
            ],
            'Nurse' => [
                'stream' => '+2 Science',
                'bachelor' => 'BSc Nursing or ANM',
                'master' => 'MSc Nursing',
                'institutions' => ['TU', 'KU', 'BPKIHS'],
                'duration' => '4-5 years'
            ],
            'Journalist' => [
                'stream' => '+2 Any Stream',
                'bachelor' => 'BA Journalism or Mass Communication',
                'master' => 'MA Journalism or Mass Communication',
                'institutions' => ['TU', 'PU', 'KU'],
                'duration' => '4-6 years'
            ],
            'Marketing Manager' => [
                'stream' => '+2 Management',
                'bachelor' => 'BBS or BBA (Marketing)',
                'master' => 'MBS or MBA (Marketing)',
                'institutions' => ['TU', 'PU', 'KU'],
                'duration' => '5-7 years'
            ],
            'Accountant' => [
                'stream' => '+2 Management',
                'bachelor' => 'BBS or BBA (Accounting)',
                'master' => 'MBS or CA',
                'institutions' => ['TU', 'PU', 'KU'],
                'duration' => '5-7 years'
            ],
            'Lawyer' => [
                'stream' => '+2 Any Stream',
                'bachelor' => 'LLB',
                'master' => 'LLM',
                'institutions' => ['TU', 'KU', 'Private Law Colleges'],
                'duration' => '5-7 years'
            ],
            'Chef' => [
                'stream' => '+2 Any Stream',
                'bachelor' => 'Diploma in Culinary Arts',
                'master' => 'Advanced Culinary Certificate',
                'institutions' => ['Hotel Management Colleges', 'Private Culinary Schools'],
                'duration' => '2-4 years'
            ],
            'Photographer' => [
                'stream' => '+2 Any Stream',
                'bachelor' => 'Diploma in Photography',
                'master' => 'Advanced Photography Course',
                'institutions' => ['Private Art Schools', 'Media Colleges'],
                'duration' => '1-3 years'
            ],
            'Tourism Guide' => [
                'stream' => '+2 Any Stream',
                'bachelor' => 'BHM or Tourism Management',
                'master' => 'MHM or MBA (Tourism)',
                'institutions' => ['TU', 'PU', 'KU'],
                'duration' => '4-6 years'
            ],
            'Agricultural Officer' => [
                'stream' => '+2 Science',
                'bachelor' => 'BSc Agriculture',
                'master' => 'MSc Agriculture',
                'institutions' => ['TU', 'KU', 'NARC'],
                'duration' => '5-7 years'
            ],
            'Bank Manager' => [
                'stream' => '+2 Management',
                'bachelor' => 'BBS or BBA (Finance)',
                'master' => 'MBS or MBA (Finance)',
                'institutions' => ['TU', 'PU', 'KU'],
                'duration' => '5-7 years'
            ],
            'Social Worker' => [
                'stream' => '+2 Any Stream',
                'bachelor' => 'BSW (Bachelor of Social Work)',
                'master' => 'MSW (Master of Social Work)',
                'institutions' => ['TU', 'PU', 'KU'],
                'duration' => '4-6 years'
            ],
            'Entrepreneur' => [
                'stream' => '+2 Any Stream',
                'bachelor' => 'BBS or BBA or BSc',
                'master' => 'MBS or MBA or MSc',
                'institutions' => ['Any University'],
                'duration' => '4-6 years'
            ],
            'Cybersecurity Specialist' => [
                'stream' => '+2 Science (Computer)',
                'bachelor' => 'BSc Computer Science or BIT',
                'master' => 'MSc Cybersecurity or MBA (IT)',
                'institutions' => ['TU', 'PU', 'KU'],
                'duration' => '6-8 years'
            ],
            'Environmental Scientist' => [
                'stream' => '+2 Science',
                'bachelor' => 'BSc Environmental Science',
                'master' => 'MSc Environmental Science',
                'institutions' => ['TU', 'KU'],
                'duration' => '5-7 years'
            ],
            'Human Resources Manager' => [
                'stream' => '+2 Management',
                'bachelor' => 'BBS or BBA (HR)',
                'master' => 'MBS or MBA (HR)',
                'institutions' => ['TU', 'PU', 'KU'],
                'duration' => '5-7 years'
            ],
            'Architect' => [
                'stream' => '+2 Science',
                'bachelor' => 'B.Arch',
                'master' => 'M.Arch',
                'institutions' => ['IOE/TU', 'PU'],
                'duration' => '6-8 years'
            ],
            'Content Writer' => [
                'stream' => '+2 Any Stream',
                'bachelor' => 'BA English or Mass Communication',
                'master' => 'MA English or Mass Communication',
                'institutions' => ['TU', 'PU', 'KU'],
                'duration' => '4-6 years'
            ],
            'UX Designer' => [
                'stream' => '+2 Science (Computer)',
                'bachelor' => 'BSc Computer Science or BIT',
                'master' => 'MSc Computer Science or MBA (IT)',
                'institutions' => ['TU', 'PU', 'KU'],
                'duration' => '6-8 years'
            ],
            'Financial Advisor' => [
                'stream' => '+2 Management',
                'bachelor' => 'BBS or BBA (Finance)',
                'master' => 'MBS or MBA (Finance) or CA',
                'institutions' => ['TU', 'PU', 'KU'],
                'duration' => '5-7 years'
            ],
            'Event Planner' => [
                'stream' => '+2 Any Stream',
                'bachelor' => 'BHM or BA/Mass Communication',
                'master' => 'MHM or MBA',
                'institutions' => ['TU', 'PU', 'KU'],
                'duration' => '4-6 years'
            ],
            'Fitness Trainer' => [
                'stream' => '+2 Any Stream',
                'bachelor' => 'BSc Physical Education or Diploma in Fitness',
                'master' => 'MSc Sports Science',
                'institutions' => ['TU', 'Private Colleges'],
                'duration' => '3-5 years'
            ],
            'Translator' => [
                'stream' => '+2 Any Stream',
                'bachelor' => 'BA (Language) or MA (Language)',
                'master' => 'MA (Language) or Diploma in Translation',
                'institutions' => ['TU', 'PU', 'KU'],
                'duration' => '4-6 years'
            ]
        ];
        
        // Default pathway if career not specifically mapped
        $default_pathway = [
            'stream' => '+2 Any Stream',
            'bachelor' => 'Bachelor Degree',
            'master' => 'Master Degree',
            'institutions' => ['University'],
            'duration' => '4-6 years'
        ];
        
        // Get pathway for this career
        $pathway = isset($nepal_pathways[$career['career_name']]) ? 
                   $nepal_pathways[$career['career_name']] : $default_pathway;
        
        $pathways[] = [
            'career' => $career,
            'pathway' => $pathway
        ];
    }
    
    return $pathways;
}

$pathways = getEducationalPathways($top_careers);

include 'header.php';
?>

<style>
.profile-header {
    background: #2c3e50;
    color: white;
    padding: 2rem 0;
    border-radius: 15px;
    margin-bottom: 2rem;
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
}

.career-card {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    height: 100%;
    border: none;
}

.career-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.2);
}

.career-card-header {
    background: #3498db;
    color: white;
    padding: 1.5rem;
    text-align: center;
}

.career-title {
    font-size: 1.4rem;
    font-weight: 700;
    margin: 0;
}

.career-card-body {
    padding: 1.5rem;
}

.career-description {
    color: #555;
    line-height: 1.6;
    margin-bottom: 1rem;
}

.detail-item {
    display: flex;
    align-items: center;
    margin-bottom: 0.5rem;
}

.skills-list {
    list-style-type: none;
    padding: 0;
    margin: 0;
}

.skills-list li {
    background: #e8f4fc;
    color: #3498db;
    padding: 0.3rem 0.8rem;
    border-radius: 20px;
    display: inline-block;
    margin: 0.2rem;
    font-size: 0.85rem;
}

.pathway-card {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    height: 100%;
    border: none;
}

.pathway-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.2);
}

.pathway-header {
    background: #27ae60;
    color: white;
    padding: 1.5rem;
    text-align: center;
}

.pathway-title {
    font-size: 1.4rem;
    font-weight: 700;
    margin: 0;
}

.pathway-body {
    padding: 1.5rem;
}

.pathway-step {
    margin-bottom: 1.5rem;
}

.pathway-step:last-child {
    margin-bottom: 0;
}

.step-label {
    font-weight: 600;
    color: #27ae60;
    margin-bottom: 0.3rem;
}

.step-value {
    color: #333;
}

.tab-content {
    padding: 2rem 0;
}

.nav-tabs .nav-link {
    font-weight: 600;
    color: #333;
    border: none;
    padding: 1rem 1.5rem;
}

.nav-tabs .nav-link.active {
    color: #3498db;
    border: none;
    border-bottom: 3px solid #3498db;
    background: transparent;
}

.progress-bar-custom {
    height: 10px;
    background: #eee;
    border-radius: 5px;
    overflow: hidden;
}

.progress-bar-fill {
    height: 100%;
    background: #3498db;
    border-radius: 5px;
    width: 0;
    transition: width 1s ease-in-out;
}

.personality-trait {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
}

.personality-trait:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

.advanced-badge {
    background: #f39c12;
    color: white;
    font-weight: 600;
    box-shadow: 0 4px 15px rgba(243, 156, 18, 0.3);
    border-radius: 30px;
}

.score-card {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    text-align: center;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.score-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.2);
}

.match-score {
    background: #9b59b6;
    color: white;
    padding: 1rem;
    border-radius: 10px;
    text-align: center;
    font-weight: 600;
    margin-top: 1rem;
}

.score-value {
    font-size: 1.5rem;
    display: block;
}
</style>

<div class="container py-5">
    <div class="profile-header text-center">
        <div class="container">
            <h1 class="display-4 fw-bold mb-3">Welcome, <?= htmlspecialchars($student_name) ?>!</h1>
            <p class="lead mb-4">Your personalized career guidance dashboard</p>
            <div class="d-inline-block px-4 py-2 rounded-pill advanced-badge">
                <i class="fas fa-star me-2"></i>Advanced Career Insights
            </div>
        </div>
    </div>
    
    <!-- Retake Assessment Button -->
    <div class="text-center mb-5">
        <a href="assessment.php?retake=true" class="btn btn-primary btn-lg">
            <i class="fas fa-redo me-2"></i>Retake Career Assessment
        </a>
    </div>
    
    <?php if($personality_completed && $skills_completed): ?>
    <!-- Navigation Tabs -->
    <ul class="nav nav-tabs justify-content-center mb-5" id="profileTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab">
                <i class="fas fa-user me-2"></i>Profile Overview
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="careers-tab" data-bs-toggle="tab" data-bs-target="#careers" type="button" role="tab">
                <i class="fas fa-briefcase me-2"></i>Top Careers
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pathway-tab" data-bs-toggle="tab" data-bs-target="#pathway" type="button" role="tab">
                <i class="fas fa-road me-2"></i>Educational Pathway
            </button>
        </li>
    </ul>
    
    <div class="tab-content" id="profileTabContent">
        <!-- Profile Overview Tab -->
        <div class="tab-pane fade show active" id="profile" role="tabpanel">
            <div class="row mb-5">
                <div class="col-md-6 mb-4">
                    <div class="score-card">
                        <i class="fas fa-user-circle fa-2x text-primary mb-3"></i>
                        <h5>Personality Score</h5>
                        <h3 class="text-primary fw-bold"><?= round(($_SESSION['personality_score'] / 24) * 100) ?>/100</h3>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="score-card">
                        <i class="fas fa-lightbulb fa-2x text-success mb-3"></i>
                        <h5>Skills Score</h5>
                        <h3 class="text-success fw-bold"><?= round(($_SESSION['skills_score'] / 30) * 100) ?>/100</h3>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-12">
                    <div class="text-center mb-5">
                        <h3 class="fw-bold mb-3"><i class="fas fa-user-alt me-2"></i>Your Personality Profile</h3>
                        <p class="text-muted">Discover your unique personality traits and how they align with career opportunities</p>
                    </div>
                    <div class="row">
                        <?php foreach($traits as $trait_name => $trait_value): ?>
                        <div class="col-lg-3 col-md-6 col-6 mb-4">
                            <div class="personality-trait text-center h-100 d-flex flex-column justify-content-center">
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
                                    <div class="progress-bar-custom mt-3">
                                        <div class="progress-bar-fill" data-width="<?= $trait_value*10 ?>"></div>
                                    </div>
                                    <div class="mt-2 fw-bold"><?= round($trait_value) ?>/10</div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Top Careers Tab -->
        <div class="tab-pane fade" id="careers" role="tabpanel">
            <div class="text-center mb-5">
                <h3 class="fw-bold mb-3"><i class="fas fa-briefcase me-2"></i>Your Top Career Matches</h3>
                <p class="text-muted">These careers align best with your skills and personality traits</p>
            </div>
            
            <div class="row">
                <?php foreach($top_careers as $match): ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="career-card h-100">
                        <div class="career-card-header">
                            <h4 class="career-title"><?= htmlspecialchars($match['career']['career_name']) ?></h4>
                        </div>
                        <div class="career-card-body">
                            <p class="career-description"><?= htmlspecialchars(substr($match['career']['description'], 0, 120)) ?>...</p>
                            
                            <div class="career-details mt-3">
                                <div class="detail-item">
                                    <i class="fas fa-money-bill-wave text-success me-2"></i>
                                    <span><?= htmlspecialchars($match['career']['salary_range']) ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="career-card-footer p-3">
                            <div class="match-score">
                                Match Score: <span class="score-value"><?= round($match['score']) ?>%</span>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <!-- Educational Pathway Tab -->
        <div class="tab-pane fade" id="pathway" role="tabpanel">
            <div class="text-center mb-5">
                <h3 class="fw-bold mb-3"><i class="fas fa-road me-2"></i>Your Educational Pathway</h3>
                <p class="text-muted">Recommended educational steps to achieve your top career matches</p>
            </div>
            
            <div class="row">
                <?php foreach($pathways as $path): ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="pathway-card h-100">
                        <div class="pathway-header">
                            <h4 class="pathway-title"><?= htmlspecialchars($path['career']['career_name']) ?></h4>
                        </div>
                        <div class="pathway-body">
                            <div class="pathway-step">
                                <div class="step-label">After SEE/+2:</div>
                                <div class="step-value"><?= htmlspecialchars($path['pathway']['stream']) ?></div>
                            </div>
                            
                            <div class="pathway-step">
                                <div class="step-label">Bachelor's Degree:</div>
                                <div class="step-value"><?= htmlspecialchars($path['pathway']['bachelor']) ?></div>
                            </div>
                            
                            <div class="pathway-step">
                                <div class="step-label">Master's Degree (Optional):</div>
                                <div class="step-value"><?= htmlspecialchars($path['pathway']['master']) ?></div>
                            </div>
                            
                            <div class="pathway-step">
                                <div class="step-label">Duration:</div>
                                <div class="step-value"><?= htmlspecialchars($path['pathway']['duration']) ?></div>
                            </div>
                            
                            <div class="pathway-step">
                                <div class="step-label">Institutions:</div>
                                <div class="step-value">
                                    <?php foreach($path['pathway']['institutions'] as $institution): ?>
                                        <span class="badge bg-info me-1"><?= htmlspecialchars($institution) ?></span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
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
    const progressBars = document.querySelectorAll('.progress-bar-fill');
    progressBars.forEach(bar => {
        const width = bar.getAttribute('data-width');
        bar.style.width = width + '%';
    });
});

// Tab navigation enhancement
document.addEventListener('DOMContentLoaded', function() {
    const tabLinks = document.querySelectorAll('.nav-tabs .nav-link');
    tabLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            // Remove active class from all tabs
            tabLinks.forEach(l => l.classList.remove('active'));
            // Add active class to clicked tab
            this.classList.add('active');
        });
    });
});
</script>

<?php include 'footer.php'; ?>