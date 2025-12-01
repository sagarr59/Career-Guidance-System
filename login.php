<?php
// Start session and database connection BEFORE any output
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'db.php';

$message = "";
$success = "";

// Check for success message from registration
if(isset($_SESSION['success'])){
    $success = $_SESSION['success'];
    unset($_SESSION['success']);
}

// Check if form is submitted
if(isset($_POST['login'])){

    // 1️⃣ Sanitize inputs
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // 2️⃣ Backend Validations
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $message = "Invalid email format.";
    } elseif(empty($password)){
        $message = "Password is required.";
    } else {
        // 3️⃣ Check if email exists in DB
        $result = $conn->query("SELECT * FROM students WHERE email='$email'");
        if($result->num_rows > 0){
            $user = $result->fetch_assoc();
            if(password_verify($password, $user['password'])){
                // 4️⃣ Successful login
                $_SESSION['student_id'] = $user['id'];
                $_SESSION['student_name'] = $user['name'];
                $_SESSION['student_email'] = $user['email'];
                header("Location: userprofile.php");
                exit;
            } else {
                $message = "Incorrect password.";
            }
        } else {
            $message = "Email not registered. Please sign up.";
        }
    }
}

// Now include header after processing
include 'header.php';
?>

<!-- LOGIN FORM -->
<section class="py-5" style="min-height: 85vh; display: flex; align-items: center;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5" data-aos="zoom-in">
                <div class="card shadow-lg border-0 p-4" style="border-radius: 20px;">
                    <div class="text-center mb-4">
                        <div class="mb-3" style="font-size: 3.5rem; color: #27ae60;">
                            <i class="fas fa-sign-in-alt"></i>
                        </div>
                        <h3 class="fw-bold" style="color: #2c3e50;">Welcome Back!</h3>
                        <p class="text-muted">Login to continue your career journey</p>
                    </div>

                    <?php if($success): ?>
                        <div class='alert alert-success alert-dismissible fade show' role='alert'>
                            <i class='fas fa-check-circle me-2'></i><?= $success ?>
                            <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                        </div>
                    <?php endif; ?>

                    <?php if($message): ?>
                        <div class='alert alert-danger alert-dismissible fade show' role='alert'>
                            <i class='fas fa-exclamation-circle me-2'></i><?= $message ?>
                            <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                        </div>
                    <?php endif; ?>

                    <form method="post" id="loginForm">
                        <div class="mb-4">
                            <label class="form-label fw-semibold" style="color: #2c3e50;">
                                <i class="fas fa-envelope me-2"></i>Email Address
                            </label>
                            <input type="email" 
                                   name="email" 
                                   class="form-control form-control-lg" 
                                   placeholder="you@example.com" 
                                   required 
                                   style="border-radius: 10px; border: 2px solid #e8ecef;">
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold" style="color: #2c3e50;">
                                <i class="fas fa-lock me-2"></i>Password
                            </label>
                            <div class="input-group">
                                <input type="password" 
                                       name="password" 
                                       id="password" 
                                       class="form-control form-control-lg" 
                                       placeholder="••••••••" 
                                       required 
                                       style="border-radius: 10px 0 0 10px; border: 2px solid #e8ecef;">
                                <button class="btn btn-outline-secondary" 
                                        type="button" 
                                        onclick="togglePassword('password', 'toggleIcon')" 
                                        style="border-radius: 0 10px 10px 0; border: 2px solid #e8ecef; border-left: none;">
                                    <i class="fas fa-eye" id="toggleIcon"></i>
                                </button>
                            </div>
                        </div>

                        <button type="submit" 
                                name="login" 
                                class="btn btn-success w-100 btn-lg mt-3" 
                                style="border-radius: 10px; padding: 15px; font-weight: 600;">
                            <i class="fas fa-sign-in-alt me-2"></i>Login to Dashboard
                        </button>

                        <div class="text-center mt-4">
                            <p class="text-muted mb-0">
                                Don't have an account? 
                                <a href="register.php" class="fw-bold" style="color: #3498db; text-decoration: none;">
                                    Sign Up Free <i class="fas fa-arrow-right"></i>
                                </a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FRONTEND VALIDATION -->
<script src="assets/js/auth-utils.js"></script>
<script>
document.getElementById('loginForm').addEventListener('submit', function(e){
    let email = document.querySelector('input[name="email"]').value.trim();
    let password = document.querySelector('input[name="password"]').value;

    if(!/^\S+@\S+\.\S+$/.test(email)){
        e.preventDefault();
        alert('⚠️ Enter a valid email address.');
        return false;
    } else if(password.length === 0){
        e.preventDefault();
        alert('⚠️ Password is required.');
        return false;
    }
});
</script>

<?php include 'footer.php'; ?>
