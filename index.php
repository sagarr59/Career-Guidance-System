<?php
include 'header.php'; // This now starts the session
include 'db.php';

// Check for logout message
$logout_message = '';
if(isset($_SESSION['logout_message'])){
    $logout_message = $_SESSION['logout_message'];
    unset($_SESSION['logout_message']);
}

// Fetch quick stats with error handling
try {
    $careers_result = $conn->query("SELECT COUNT(*) as total FROM careers");
    $careers_count = $careers_result ? $careers_result->fetch_assoc()['total'] : 0;
} catch (Exception $e) {
    $careers_count = 0;
}

try {
    $skills_result = $conn->query("SELECT COUNT(*) as total FROM skills");
    $skills_count = $skills_result ? $skills_result->fetch_assoc()['total'] : 0;
} catch (Exception $e) {
    $skills_count = 0;
}

try {
    $users_result = $conn->query("SELECT COUNT(*) as total FROM students");
    $users_count = $users_result ? $users_result->fetch_assoc()['total'] : 0;
} catch (Exception $e) {
    $users_count = 0;
}

try {
    $matches_result = $conn->query("SELECT COUNT(DISTINCT user_id) as total FROM user_career_matches");
    $matches_count = $matches_result ? $matches_result->fetch_assoc()['total'] : 0;
} catch (Exception $e) {
    $matches_count = 0;
}
?>

<style>
/* Modern Homepage Styles */
body {
    background-color: #f8f9fa;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.hero-section {
    background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
    color: white;
    position: relative;
    overflow: hidden;
    min-height: 100vh;
    display: flex;
    align-items: center;
    padding: 80px 0;
}

.hero-content {
    max-width: 1200px;
    margin: 0 auto;
    text-align: center;
    padding: 0 20px;
}

.hero-title {
    font-size: 3.5rem;
    font-weight: 800;
    margin-bottom: 20px;
    line-height: 1.2;
    text-shadow: 0 2px 10px rgba(0,0,0,0.2);
}

.hero-subtitle {
    font-size: 1.5rem;
    font-weight: 400;
    margin-bottom: 40px;
    opacity: 0.9;
    max-width: 700px;
    margin-left: auto;
    margin-right: auto;
}

.stat-card-modern {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    transition: all 0.3s ease;
    height: 100%;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.2);
}

.stat-card-modern:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 40px rgba(0,0,0,0.2);
}

.stat-number {
    font-size: 3rem;
    font-weight: 800;
    color: #3498db;
    margin-bottom: 10px;
}

.stat-label {
    font-size: 1.1rem;
    color: #2c3e50;
    font-weight: 600;
}

.feature-section {
    padding: 100px 0;
    background: white;
}

.section-title {
    text-align: center;
    margin-bottom: 60px;
}

.section-title h2 {
    font-size: 2.5rem;
    font-weight: 800;
    color: #2c3e50;
    margin-bottom: 15px;
}

.section-title p {
    font-size: 1.2rem;
    color: #7f8c8d;
    max-width: 700px;
    margin: 0 auto;
}

.feature-card-modern {
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 25px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    height: 100%;
    padding: 40px 30px;
    text-align: center;
    border: 1px solid #eee;
}

.feature-card-modern:hover {
    transform: translateY(-15px);
    box-shadow: 0 15px 40px rgba(0,0,0,0.15);
}

.feature-icon-modern {
    width: 90px;
    height: 90px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    margin: 0 auto 25px;
    font-size: 2.5rem;
    background: linear-gradient(135deg, #3498db 0%, #2c3e50 100%);
    color: white;
    transition: all 0.3s ease;
}

.feature-card-modern:hover .feature-icon-modern {
    transform: scale(1.1) rotate(5deg);
}

.feature-card-modern h4 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 20px;
}

.feature-card-modern p {
    color: #7f8c8d;
    font-size: 1.1rem;
    line-height: 1.6;
}

.process-section {
    padding: 100px 0;
    background: #f8f9fa;
}

.process-card-modern {
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 25px rgba(0,0,0,0.08);
    padding: 40px 30px;
    transition: all 0.3s ease;
    position: relative;
    text-align: center;
    border: 1px solid #eee;
}

.process-card-modern:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 40px rgba(0,0,0,0.15);
}

.process-number-modern {
    position: absolute;
    top: -20px;
    left: 50%;
    transform: translateX(-50%);
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #3498db 0%, #2c3e50 100%);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1.2rem;
    box-shadow: 0 5px 15px rgba(52, 152, 219, 0.4);
}

.process-card-modern h4 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2c3e50;
    margin: 20px 0 15px;
}

.process-card-modern p {
    color: #7f8c8d;
    font-size: 1.1rem;
    line-height: 1.6;
}

.cta-section {
    padding: 100px 0;
    background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
    color: white;
    text-align: center;
}

.cta-title {
    font-size: 2.5rem;
    font-weight: 800;
    margin-bottom: 20px;
}

.cta-subtitle {
    font-size: 1.3rem;
    font-weight: 400;
    margin-bottom: 40px;
    max-width: 700px;
    margin-left: auto;
    margin-right: auto;
    opacity: 0.9;
}

.btn-modern {
    background: white;
    color: #2c3e50;
    border: none;
    padding: 15px 40px;
    border-radius: 50px;
    font-weight: 700;
    font-size: 1.1rem;
    transition: all 0.3s ease;
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    margin: 10px;
}

.btn-modern:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.3);
}

.btn-modern-primary {
    background: #3498db;
    color: white;
}

.btn-modern-success {
    background: #27ae60;
    color: white;
}

/* Responsive adjustments */
@media (max-width: 992px) {
    .hero-title {
        font-size: 2.8rem;
    }
    
    .hero-subtitle {
        font-size: 1.3rem;
    }
    
    .feature-card-modern {
        margin-bottom: 30px;
    }
    
    .process-card-modern {
        margin-bottom: 50px;
    }
}

@media (max-width: 768px) {
    .hero-section {
        padding: 60px 0;
    }
    
    .hero-title {
        font-size: 2.3rem;
    }
    
    .hero-subtitle {
        font-size: 1.1rem;
    }
    
    .section-title h2 {
        font-size: 2rem;
    }
    
    .section-title p {
        font-size: 1rem;
    }
    
    .stat-number {
        font-size: 2.5rem;
    }
    
    .feature-icon-modern {
        width: 70px;
        height: 70px;
        font-size: 2rem;
    }
    
    .cta-title {
        font-size: 2rem;
    }
    
    .cta-subtitle {
        font-size: 1.1rem;
    }
}
</style>

<div class="hero-section">
    <div class="container">
        <div class="hero-content">
            <h1 class="hero-title">Discover Your Perfect Career Path</h1>
            <p class="hero-subtitle">Personalized career guidance for Nepali students based on your unique skills, interests, and personality traits</p>
            <div class="mt-4">
                <?php if(!isset($_SESSION['student_id'])): ?>
                    <a href="register.php" class="btn btn-modern btn-modern-success">
                        <i class="fas fa-user-plus me-2"></i>Get Started Today
                    </a>
                <?php else: ?>
                    <a href="userprofile.php" class="btn btn-modern btn-modern-primary">
                        <i class="fas fa-chart-line me-2"></i>View My Profile
                    </a>
                <?php endif; ?>
                <a href="info.php" class="btn btn-modern">
                    <i class="fas fa-info-circle me-2"></i>Learn More
                </a>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="row mb-5">
        <div class="col-12 text-center">
            <h2 class="display-5 fw-bold mb-3">Why Choose Our Career Guidance System?</h2>
            <p class="lead text-muted">Personalized recommendations based on your unique skills and personality</p>
        </div>
    </div>
    
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="stat-card-modern p-4 text-center">
                <div class="stat-number"><?= $careers_count ?></div>
                <div class="stat-label">Career Options</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card-modern p-4 text-center">
                <div class="stat-number"><?= $skills_count ?></div>
                <div class="stat-label">Key Skills</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card-modern p-4 text-center">
                <div class="stat-number"><?= $users_count ?></div>
                <div class="stat-label">Students Guided</div>
            </div>
        </div>
        <!-- Removed the "Matches Made" section as requested -->
    </div>
    
    <div class="row mb-5">
        <div class="col-lg-4 mb-4">
            <div class="feature-card-modern">
                <div class="feature-icon-modern">
                    <i class="fas fa-user-check"></i>
                </div>
                <h4>Personalized Assessment</h4>
                <p>Comprehensive evaluation of your personality traits, skills, and preferences to match you with suitable careers.</p>
            </div>
        </div>
        
        <div class="col-lg-4 mb-4">
            <div class="feature-card-modern">
                <div class="feature-icon-modern" style="background: linear-gradient(135deg, #27ae60 0%, #2c3e50 100%);">
                    <i class="fas fa-route"></i>
                </div>
                <h4>Educational Pathway</h4>
                <p>Clear guidance on the educational steps required to achieve your recommended career in the Nepali context.</p>
            </div>
        </div>
        
        <div class="col-lg-4 mb-4">
            <div class="feature-card-modern">
                <div class="feature-icon-modern" style="background: linear-gradient(135deg, #e74c3c 0%, #2c3e50 100%);">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h4>Career Insights</h4>
                <p>Detailed information about salary expectations, job market trends, and growth opportunities in Nepal.</p>
            </div>
        </div>
    </div>
    
    <div class="row mb-5">
        <div class="col-12 text-center">
            <h2 class="display-5 fw-bold mb-3">How It Works</h2>
            <p class="lead text-muted">Simple 3-step process to discover your ideal career</p>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="process-card-modern">
                <div class="process-number-modern">1</div>
                <div class="text-center mb-3">
                    <div class="mx-auto mb-3" style="width: 70px; height: 70px; background: linear-gradient(135deg, #3498db 0%, #2c3e50 100%); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.8rem;">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <h4>Take Assessment</h4>
                </div>
                <p class="text-center">Complete our comprehensive assessment covering your skills, interests, and personality traits.</p>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="process-card-modern">
                <div class="process-number-modern">2</div>
                <div class="text-center mb-3">
                    <div class="mx-auto mb-3" style="width: 70px; height: 70px; background: linear-gradient(135deg, #27ae60 0%, #2c3e50 100%); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.8rem;">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <h4>Get Recommendations</h4>
                </div>
                <p class="text-center">Receive personalized career recommendations based on your unique profile.</p>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="process-card-modern">
                <div class="process-number-modern">3</div>
                <div class="text-center mb-3">
                    <div class="mx-auto mb-3" style="width: 70px; height: 70px; background: linear-gradient(135deg, #e74c3c 0%, #2c3e50 100%); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.8rem;">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h4>Plan Your Future</h4>
                </div>
                <p class="text-center">Get detailed educational pathways and career planning resources.</p>
            </div>
        </div>
    </div>
    
    <div class="text-center mt-5">
        <a href="info.php" class="btn btn-modern btn-modern-primary">
            <i class="fas fa-envelope me-2"></i>Contact Us
        </a>
    </div>
</div>

<?php include 'footer.php'; ?>