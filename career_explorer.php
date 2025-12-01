<?php
session_start();
include 'header.php';

// Determine target URL based on user login status
$explore_url = isset($_SESSION['student_id']) ? 'career_path_generator.php' : 'register.php';
$explore_text = isset($_SESSION['student_id']) ? 'Generate My Path' : 'Explore Careers';
?>

<style>
.career-explorer-header {
    background: #2c3e50;
    color: white;
    border-radius: 15px;
    margin-bottom: 2rem;
    padding: 3rem 2rem;
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    position: relative;
    overflow: hidden;
}

.career-category-card {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    height: 100%;
    border-left: 5px solid #3498db;
}

.career-category-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.15);
    border-left-color: #27ae60;
}

.career-icon {
    width: 70px;
    height: 70px;
    background: #3498db;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
    margin-bottom: 1.5rem;
}

.career-title {
    font-size: 1.4rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 1rem;
}

.career-description {
    color: #7f8c8d;
    margin-bottom: 1.5rem;
}

.btn-explore {
    background: #3498db;
    border: none;
    padding: 10px 20px;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-explore:hover {
    background: #2980b9;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);
}

.featured-career {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    margin-bottom: 1.5rem;
}

.featured-career:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.12);
}

.salary-badge {
    background: #27ae60;
    color: white;
    padding: 5px 15px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.9rem;
}

.growth-badge {
    background: #f39c12;
    color: white;
    padding: 5px 15px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.9rem;
}

@media (max-width: 768px) {
    .career-explorer-header {
        padding: 2rem 1.5rem;
    }
    
    .career-category-card {
        padding: 1.5rem;
    }
    
    .career-icon {
        width: 60px;
        height: 60px;
        font-size: 1.5rem;
    }
}
</style>

<div class="container py-5">
    <div class="career-explorer-header text-center">
        <h1 class="display-4 fw-bold mb-3"><i class="fas fa-compass me-2"></i>Career Explorer</h1>
        <p class="lead mb-4">Discover exciting career opportunities and find your perfect professional path</p>
        <div class="d-inline-block px-4 py-2 rounded-pill" style="background: rgba(255,255,255,0.15);">
            <i class="fas fa-lightbulb me-2"></i>Explore. Discover. Succeed.
        </div>
    </div>
    
    <div class="text-center mb-5">
        <h2 class="fw-bold mb-4">Browse Career Categories</h2>
        <p class="text-muted mb-5">Explore various fields to find careers that match your interests and skills</p>
    </div>
    
    <div class="row mb-5">
        <div class="col-md-4 mb-4">
            <div class="career-category-card">
                <div class="career-icon" style="background: #3498db;">
                    <i class="fas fa-laptop-code"></i>
                </div>
                <h3 class="career-title">Technology & IT</h3>
                <p class="career-description">Explore careers in software development, cybersecurity, data science, and emerging technologies.</p>
                <a href="<?php echo $explore_url; ?>" class="btn btn-explore">
                    <i class="fas fa-arrow-right me-2"></i><?php echo $explore_text; ?>
                </a>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="career-category-card">
                <div class="career-icon" style="background: #27ae60;">
                    <i class="fas fa-heartbeat"></i>
                </div>
                <h3 class="career-title">Healthcare</h3>
                <p class="career-description">Discover rewarding careers in medicine, nursing, pharmacy, and healthcare administration.</p>
                <a href="<?php echo $explore_url; ?>" class="btn btn-explore">
                    <i class="fas fa-arrow-right me-2"></i><?php echo $explore_text; ?>
                </a>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="career-category-card">
                <div class="career-icon" style="background: #e74c3c;">
                    <i class="fas fa-paint-brush"></i>
                </div>
                <h3 class="career-title">Creative Arts</h3>
                <p class="career-description">Find opportunities in design, media, entertainment, and artistic expression.</p>
                <a href="<?php echo $explore_url; ?>" class="btn btn-explore">
                    <i class="fas fa-arrow-right me-2"></i><?php echo $explore_text; ?>
                </a>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="career-category-card">
                <div class="career-icon" style="background: #f39c12;">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3 class="career-title">Business & Finance</h3>
                <p class="career-description">Explore careers in management, marketing, accounting, and financial services.</p>
                <a href="<?php echo $explore_url; ?>" class="btn btn-explore">
                    <i class="fas fa-arrow-right me-2"></i><?php echo $explore_text; ?>
                </a>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="career-category-card">
                <div class="career-icon" style="background: #9b59b6;">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h3 class="career-title">Education</h3>
                <p class="career-description">Discover fulfilling careers in teaching, training, and educational leadership.</p>
                <a href="<?php echo $explore_url; ?>" class="btn btn-explore">
                    <i class="fas fa-arrow-right me-2"></i><?php echo $explore_text; ?>
                </a>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="career-category-card">
                <div class="career-icon" style="background: #1abc9c;">
                    <i class="fas fa-gavel"></i>
                </div>
                <h3 class="career-title">Law & Public Service</h3>
                <p class="career-description">Explore careers in legal services, government, and public administration.</p>
                <a href="<?php echo $explore_url; ?>" class="btn btn-explore">
                    <i class="fas fa-arrow-right me-2"></i><?php echo $explore_text; ?>
                </a>
            </div>
        </div>
    </div>
    
    <div class="text-center mb-5">
        <h2 class="fw-bold mb-4">Featured Careers</h2>
        <p class="text-muted mb-5">Check out some of the most promising careers in today's job market</p>
    </div>
    
    <div class="row">
        <div class="col-lg-6 mb-4">
            <a href="<?php echo $explore_url; ?>" class="text-decoration-none">
                <div class="featured-career">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h4 class="fw-bold mb-0">Software Engineer</h4>
                        <div class="salary-badge">Avg. Rs. 800,000/year</div>
                    </div>
                    <p class="text-muted mb-3">Design, develop, and maintain software applications and systems.</p>
                    <div class="d-flex justify-content-between">
                        <span class="growth-badge">High Growth</span>
                        <span class="text-muted">Technology Sector</span>
                    </div>
                </div>
            </a>
        </div>
        
        <div class="col-lg-6 mb-4">
            <a href="<?php echo $explore_url; ?>" class="text-decoration-none">
                <div class="featured-career">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h4 class="fw-bold mb-0">Data Scientist</h4>
                        <div class="salary-badge">Avg. Rs. 950,000/year</div>
                    </div>
                    <p class="text-muted mb-3">Analyze complex data to help organizations make informed decisions.</p>
                    <div class="d-flex justify-content-between">
                        <span class="growth-badge">Rapid Growth</span>
                        <span class="text-muted">Technology Sector</span>
                    </div>
                </div>
            </a>
        </div>
        
        <div class="col-lg-6 mb-4">
            <a href="<?php echo $explore_url; ?>" class="text-decoration-none">
                <div class="featured-career">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h4 class="fw-bold mb-0">Registered Nurse</h4>
                        <div class="salary-badge">Avg. Rs. 450,000/year</div>
                    </div>
                    <p class="text-muted mb-3">Provide patient care and support in hospitals, clinics, and healthcare facilities.</p>
                    <div class="d-flex justify-content-between">
                        <span class="growth-badge">Steady Growth</span>
                        <span class="text-muted">Healthcare Sector</span>
                    </div>
                </div>
            </a>
        </div>
        
        <div class="col-lg-6 mb-4">
            <a href="<?php echo $explore_url; ?>" class="text-decoration-none">
                <div class="featured-career">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h4 class="fw-bold mb-0">Marketing Manager</h4>
                        <div class="salary-badge">Avg. Rs. 600,000/year</div>
                    </div>
                    <p class="text-muted mb-3">Develop and execute marketing strategies to promote products and services.</p>
                    <div class="d-flex justify-content-between">
                        <span class="growth-badge">Moderate Growth</span>
                        <span class="text-muted">Business Sector</span>
                    </div>
                </div>
            </a>
        </div>
    </div>
    
    <div class="text-center mt-5">
        <div class="card border-0 shadow-sm" style="border-radius: 15px; background: linear-gradient(135deg, #3498db 0%, #2c3e50 100%); color: white;">
            <div class="card-body p-5">
                <h3 class="fw-bold mb-3"><i class="fas fa-user-graduate me-2"></i>Ready to Find Your Perfect Career?</h3>
                <p class="mb-4" style="opacity: 0.9;">Take our comprehensive assessment to get personalized career recommendations based on your unique skills and interests.</p>
                <a href="<?php echo $explore_url; ?>" class="btn btn-light btn-lg px-4 py-2 fw-bold" style="border-radius: 8px;">
                    <i class="fas fa-rocket me-2"></i><?php echo isset($_SESSION['student_id']) ? 'Generate My Career Path' : 'Get Started Now'; ?>
                </a>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>