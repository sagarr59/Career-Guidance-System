<?php
include 'header.php';

// Handle form submission
$message_sent = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name'] ?? '');
    $email = htmlspecialchars($_POST['email'] ?? '');
    $subject = htmlspecialchars($_POST['subject'] ?? '');
    $message = htmlspecialchars($_POST['message'] ?? '');
    
    // In a real application, you would send an email here
    // For now, we'll just set a success flag
    if ($name && $email && $subject && $message) {
        $message_sent = true;
    }
}
?>

<style>
.info-header {
    background: #2c3e50;
    color: white;
    border-radius: 15px;
    margin-bottom: 2rem;
    padding: 3rem 2rem;
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    position: relative;
    overflow: hidden;
}

.team-member {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    height: 100%;
    text-align: center;
}

.team-member:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.15);
}

.team-member img {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    border: 5px solid #ecf0f1;
    margin-bottom: 1rem;
}

.feature-card {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    height: 100%;
}

.feature-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.15);
}

.feature-icon {
    width: 80px;
    height: 80px;
    background: #3498db;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    margin: 0 auto 1.5rem;
}

.stats-card {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    text-align: center;
    transition: all 0.3s ease;
}

.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.15);
}

.stats-number {
    font-size: 2.5rem;
    font-weight: 700;
    color: #3498db;
    margin-bottom: 0.5rem;
}

.mission-vision {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}

.contact-card {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    height: 100%;
}

.contact-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.15);
}

.contact-icon {
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

.form-control {
    border-radius: 8px;
    padding: 12px 15px;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #3498db;
    box-shadow: 0 0 0 0.25rem rgba(52, 152, 219, 0.25);
}

.btn-primary {
    background: #3498db;
    border: none;
    padding: 12px 30px;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    background: #2980b9;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);
}

.alert-success {
    border-radius: 10px;
    border: none;
    box-shadow: 0 3px 10px rgba(0,0,0,0.08);
}

.map-container {
    height: 300px;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}

@media (max-width: 768px) {
    .info-header {
        padding: 2rem 1.5rem;
    }
    
    .team-member {
        padding: 1.5rem;
    }
    
    .feature-card {
        padding: 1.5rem;
    }
    
    .stats-card {
        padding: 1.5rem;
    }
    
    .mission-vision {
        padding: 1.5rem;
    }
    
    .contact-card {
        padding: 1.5rem;
    }
    
    .contact-icon {
        width: 60px;
        height: 60px;
        font-size: 1.5rem;
    }
}
</style>

<div class="container py-5">
    <div class="info-header text-center">
        <h1 class="display-4 fw-bold mb-3"><i class="fas fa-info-circle me-2"></i>Information Center</h1>
        <p class="lead mb-4">Learn about us and get in touch with our team</p>
    </div>
    
    <!-- About Section -->
    <div class="row mb-5">
        <div class="col-12">
            <h2 class="fw-bold mb-4 text-center"><i class="fas fa-building me-2"></i>About Career Guidance System</h2>
        </div>
        
        <div class="col-lg-6 mb-4">
            <div class="mission-vision">
                <h3 class="fw-bold mb-4"><i class="fas fa-bullseye me-2 text-primary"></i>Our Mission</h3>
                <p class="mb-4">To provide Nepali students with accurate, personalized career guidance that helps them make informed decisions about their future based on their unique skills, interests, and personality traits.</p>
                
                <h3 class="fw-bold mb-4"><i class="fas fa-eye me-2 text-success"></i>Our Vision</h3>
                <p class="mb-0">To become the most trusted career guidance platform in Nepal, bridging the gap between students and their ideal career paths through innovative technology and expert insights.</p>
            </div>
        </div>
        
        <div class="col-lg-6 mb-4">
            <div class="mission-vision">
                <h3 class="fw-bold mb-4"><i class="fas fa-lightbulb me-2 text-warning"></i>Why Choose Us?</h3>
                <p class="mb-4">Our system uses a comprehensive assessment approach that evaluates your personality traits, skills, and preferences to provide tailored career recommendations specifically for the Nepali job market.</p>
                
                <div class="d-flex justify-content-between text-center mt-4">
                    <div class="stats-card flex-grow-1 me-3">
                        <div class="stats-number">30+</div>
                        <div class="fw-bold">Career Options</div>
                    </div>
                    <div class="stats-card flex-grow-1 me-3">
                        <div class="stats-number">15</div>
                        <div class="fw-bold">Key Skills</div>
                    </div>
                    <div class="stats-card flex-grow-1">
                        <div class="stats-number">500+</div>
                        <div class="fw-bold">Students Guided</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="text-center mb-5">
        <h3 class="fw-bold mb-4"><i class="fas fa-star me-2"></i>Key Features</h3>
        <p class="text-muted mb-5">Our comprehensive system provides everything you need for career planning</p>
    </div>
    
    <div class="row mb-5">
        <div class="col-md-4 mb-4">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-user-check"></i>
                </div>
                <h4 class="fw-bold text-center mb-3">Personalized Assessment</h4>
                <p class="text-center">Comprehensive evaluation of your personality traits, skills, and preferences to match you with suitable careers.</p>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="feature-card">
                <div class="feature-icon" style="background: #27ae60;">
                    <i class="fas fa-route"></i>
                </div>
                <h4 class="fw-bold text-center mb-3">Educational Pathway</h4>
                <p class="text-center">Clear guidance on the educational steps required to achieve your recommended career in the Nepali context.</p>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="feature-card">
                <div class="feature-icon" style="background: #e74c3c;">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h4 class="fw-bold text-center mb-3">Career Insights</h4>
                <p class="text-center">Detailed information about salary expectations, job market trends, and growth opportunities in Nepal.</p>
            </div>
        </div>
    </div>
    
    <!-- Contact Section -->
    <div class="row mb-5">
        <div class="col-12">
            <h2 class="fw-bold mb-4 text-center mt-5 pt-5"><i class="fas fa-envelope me-2"></i>Contact Us</h2>
        </div>
        
        <?php if ($message_sent): ?>
        <div class="col-12">
            <div class="alert alert-success alert-dismissible fade show text-center mb-5" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                <strong>Thank you!</strong> Your message has been sent successfully. We'll get back to you soon.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
        <?php endif; ?>
        
        <div class="col-lg-6 mb-4">
            <div class="contact-card">
                <h3 class="fw-bold mb-4"><i class="fas fa-paper-plane me-2"></i>Send us a Message</h3>
                <form method="POST">
                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold">Full Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter your full name" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email address" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="subject" class="form-label fw-semibold">Subject</label>
                        <input type="text" class="form-control" id="subject" name="subject" placeholder="Enter subject" required>
                    </div>
                    
                    <div class="mb-4">
                        <label for="message" class="form-label fw-semibold">Message</label>
                        <textarea class="form-control" id="message" name="message" rows="5" placeholder="Enter your message" required></textarea>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-paper-plane me-2"></i>Send Message
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="col-lg-6 mb-4">
            <div class="contact-card">
                <div class="contact-icon" style="background: #27ae60;">
                    <i class="fas fa-phone-alt"></i>
                </div>
                <h4 class="fw-bold mb-3">Contact Information</h4>
                <p class="mb-3"><i class="fas fa-phone me-2"></i> Phone: +977 9818197270</p>
                <p class="mb-3"><i class="fas fa-envelope me-2"></i> Email: info@careerguidance.edu.np</p>
                <p class="mb-3"><i class="fas fa-map-marker-alt me-2"></i> Location: Kathmandu, Nepal</p>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>