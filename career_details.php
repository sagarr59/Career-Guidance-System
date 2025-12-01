<?php
session_start();
include 'header.php';
include 'db.php';
include 'algorithms/career-matching.php';

if(!isset($_SESSION['student_id'])){
    header("Location: login.php");
    exit;
}

// Get career ID from URL
$career_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($career_id <= 0) {
    header("Location: results.php");
    exit;
}

// Fetch career details
$stmt = $conn->prepare("SELECT * FROM careers WHERE id = ?");
$stmt->bind_param("i", $career_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location: results.php");
    exit;
}

$career = $result->fetch_assoc();
$stmt->close();

// Get user assessment data
$personality_score = isset($_SESSION['personality_score']) ? $_SESSION['personality_score'] : 0;
$skills_score = isset($_SESSION['skills_score']) ? $_SESSION['skills_score'] : 0;
$traits = isset($_SESSION['personality_traits']) ? json_decode($_SESSION['personality_traits'], true) : [];
$selected_skills = isset($_SESSION['assessment_step1']['skills']) ? $_SESSION['assessment_step1']['skills'] : [];

// Get skill gap analysis
$skill_gap_analysis = getSkillGapAnalysis($career['career_name'], $skills_score, $selected_skills, $conn);

// Get educational pathway
$educational_pathway = getEducationalPathway($career['career_name']);

?>
<style>
.career-detail-card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    margin-bottom: 25px;
    transition: all 0.3s ease;
}

.career-detail-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
}

.detail-header {
    background: linear-gradient(135deg, #3498db 0%, #2c3e50 100%);
    color: white;
    border-radius: 15px 15px 0 0;
    padding: 30px;
}

.detail-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 15px;
}

.detail-lead {
    font-size: 1.2rem;
    opacity: 0.9;
}

.detail-section-title {
    color: #2c3e50;
    font-weight: 600;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #3498db;
}

.info-card {
    background: white;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.08);
    height: 100%;
}

.info-card i {
    color: #3498db;
    margin-right: 10px;
}

.circular-progress {
    width: 120px;
    height: 120px;
}

.circular-svg {
    width: 100%;
    height: 100%;
}

.circle-bg {
    fill: none;
    stroke: #eee;
    stroke-width: 3;
}

.circle {
    fill: none;
    stroke-width: 3;
    stroke: #3498db;
    stroke-linecap: round;
    transform: rotate(-90deg);
    transform-origin: 50% 50%;
    transition: stroke-dasharray 0.5s ease;
}

.gap-circle {
    stroke: #e74c3c;
}

.percentage {
    fill: #2c3e50;
    font-size: 0.4em;
    text-anchor: middle;
}

.recommendations-list {
    max-height: 200px;
    overflow-y: auto;
}

.recommendations-list::-webkit-scrollbar {
    width: 6px;
}

.recommendations-list::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.recommendations-list::-webkit-scrollbar-thumb {
    background: #3498db;
    border-radius: 3px;
}

.recommendations-list li {
    padding: 8px 12px;
    border-left: 3px solid #3498db;
    margin-bottom: 8px;
    background: #f8f9fa;
    border-radius: 0 5px 5px 0;
}
</style>

<div class="container py-5">
    <div class="row">
        <div class="col-12 mb-4">
            <a href="results.php" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-2"></i>Back to Results
            </a>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12">
            <div class="career-detail-card">
                <div class="detail-header">
                    <h1 class="detail-title"><?= htmlspecialchars($career['career_name']) ?></h1>
                    <p class="detail-lead"><?= htmlspecialchars($career['description']) ?></p>
                </div>
                
                <div class="p-4">
                    <h3 class="detail-section-title">Career Overview</h3>
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="info-card">
                                <h5><i class="fas fa-graduation-cap"></i>Education Required</h5>
                                <p class="mt-3"><?= htmlspecialchars($career['education_required']) ?></p>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-4">
                            <div class="info-card">
                                <h5><i class="fas fa-money-bill-wave"></i>Salary Range</h5>
                                <p class="mt-3"><?= htmlspecialchars($career['salary_range']) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Skill Gap Analysis Section -->
    <div class="row">
        <div class="col-12">
            <div class="career-detail-card">
                <div class="p-4">
                    <h3 class="detail-section-title"><i class="fas fa-chart-bar me-2"></i>Skill Gap Analysis</h3>
                    
                    <div class="row">
                        <div class="col-lg-4 mb-4">
                            <div class="info-card text-center">
                                <div class="circular-progress mx-auto mb-3" data-percent="<?= $skill_gap_analysis['current_level'] ?>">
                                    <svg viewBox="0 0 36 36" class="circular-svg">
                                        <path class="circle-bg"
                                            d="M18 2.0845
                                              a 15.9155 15.9155 0 0 1 0 31.831
                                              a 15.9155 15.9155 0 0 1 0 -31.831"
                                        />
                                        <path class="circle"
                                            stroke-dasharray="<?= $skill_gap_analysis['current_level'] ?>, 100"
                                            d="M18 2.0845
                                              a 15.9155 15.9155 0 0 1 0 31.831
                                              a 15.9155 15.9155 0 0 1 0 -31.831"
                                        />
                                        <text x="18" y="20.5" class="percentage"><?= $skill_gap_analysis['current_level'] ?>%</text>
                                    </svg>
                                </div>
                                <h5 class="fw-bold">Current Skill Level</h5>
                                <p class="text-muted">Based on your assessment</p>
                            </div>
                        </div>
                        
                        <div class="col-lg-4 mb-4">
                            <div class="info-card text-center">
                                <div class="circular-progress mx-auto mb-3" data-percent="<?= $skill_gap_analysis['gap'] ?>">
                                    <svg viewBox="0 0 36 36" class="circular-svg">
                                        <path class="circle-bg"
                                            d="M18 2.0845
                                              a 15.9155 15.9155 0 0 1 0 31.831
                                              a 15.9155 15.9155 0 0 1 0 -31.831"
                                        />
                                        <path class="circle gap-circle"
                                            stroke-dasharray="<?= $skill_gap_analysis['gap'] ?>, 100"
                                            d="M18 2.0845
                                              a 15.9155 15.9155 0 0 1 0 31.831
                                              a 15.9155 15.9155 0 0 1 0 -31.831"
                                        />
                                        <text x="18" y="20.5" class="percentage"><?= $skill_gap_analysis['gap'] ?>%</text>
                                    </svg>
                                </div>
                                <h5 class="fw-bold">Skill Gap</h5>
                                <p class="text-muted">Skills you need to develop</p>
                            </div>
                        </div>
                        
                        <div class="col-lg-4 mb-4">
                            <div class="info-card">
                                <h5 class="fw-bold"><i class="fas fa-lightbulb me-2"></i>Recommendations</h5>
                                <p>To improve your match for this career, focus on developing these key skills:</p>
                                <ul class="recommendations-list">
                                    <?php foreach(array_slice($skill_gap_analysis['missing_skills'], 0, 5) as $skill): ?>
                                    <li><?= htmlspecialchars($skill) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Educational Pathway Section -->
    <div class="row">
        <div class="col-12">
            <div class="career-detail-card">
                <div class="p-4">
                    <h3 class="detail-section-title"><i class="fas fa-university me-2"></i>Educational Pathway</h3>
                    
                    <div class="row">
                        <div class="col-md-4 mb-4">
                            <div class="info-card">
                                <h5 class="fw-bold">Stream</h5>
                                <p class="mt-3"><?= htmlspecialchars($educational_pathway['stream']) ?></p>
                            </div>
                        </div>
                        
                        <div class="col-md-4 mb-4">
                            <div class="info-card">
                                <h5 class="fw-bold">Bachelor's Degree</h5>
                                <p class="mt-3"><?= htmlspecialchars($educational_pathway['bachelor']) ?></p>
                            </div>
                        </div>
                        
                        <div class="col-md-4 mb-4">
                            <div class="info-card">
                                <h5 class="fw-bold">Master's Degree</h5>
                                <p class="mt-3"><?= htmlspecialchars($educational_pathway['master']) ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <h5 class="fw-bold">Additional Information</h5>
                        <p><?= htmlspecialchars($educational_pathway['description']) ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.circular-progress {
    width: 120px;
    height: 120px;
}

.circular-svg {
    width: 100%;
    height: 100%;
}

.circle-bg {
    fill: none;
    stroke: #eee;
    stroke-width: 3;
}

.circle {
    fill: none;
    stroke-width: 3;
    stroke: #3498db;
    stroke-linecap: round;
    transform: rotate(-90deg);
    transform-origin: 50% 50%;
    transition: stroke-dasharray 0.5s ease;
}

.gap-circle {
    stroke: #e74c3c;
}

.percentage {
    fill: #2c3e50;
    font-size: 0.4em;
    text-anchor: middle;
}
</style>

<?php include 'footer.php'; ?>