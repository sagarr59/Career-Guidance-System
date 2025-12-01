<?php
// Start session and database connection BEFORE any output
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'db.php';

$message = "";

// Check if form is submitted
if(isset($_POST['register'])){

    // 1️⃣ Sanitize inputs
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // 2️⃣ Backend Validations
    if(strlen($name) < 3){
        $message = "Name must be at least 3 characters.";
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $message = "Invalid email address.";
    } elseif(strlen($password) < 6){
        $message = "Password must be at least 6 characters.";
    } elseif($password !== $confirm_password){
        $message = "Passwords do not match.";
    } else {
        // 3️⃣ Check if email already exists
        $check = $conn->query("SELECT * FROM students WHERE email='$email'");
        if($check->num_rows > 0){
            $message = "Email already registered. Please login.";
        } else {
            // 4️⃣ Insert into database
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insert = $conn->query("INSERT INTO students (name, email, password) VALUES ('$name','$email','$hashed_password')");

            if($insert){
                $_SESSION['success'] = "Account created successfully! Please login.";
                header("Location: login.php");
                exit;
            } else {
                $message = "Registration failed. Try again.";
            }
        }
    }
}

// Now include header after processing
include 'header.php';
?>

<!-- Registration Form -->
<section class="py-5" style="min-height: 85vh; display: flex; align-items: center;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5" data-aos="zoom-in">
                <div class="card shadow-lg border-0 p-4" style="border-radius: 20px;">
                    <div class="text-center mb-4">
                        <div class="mb-3" style="font-size: 3.5rem; color: #3498db;">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <h3 class="fw-bold" style="color: #2c3e50;">Create Account</h3>
                        <p class="text-muted">Join thousands of students finding their career path</p>
                    </div>

                    <?php if($message): ?>
                        <div class='alert alert-danger alert-dismissible fade show' role='alert'>
                            <i class='fas fa-exclamation-circle me-2'></i><?= $message ?>
                            <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                        </div>
                    <?php endif; ?>

                    <form method="post" id="registerForm">
                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="color: #2c3e50;">
                                <i class="fas fa-user me-2"></i>Full Name
                            </label>
                            <input type="text" 
                                   name="name" 
                                   class="form-control form-control-lg" 
                                   placeholder="Enter your full name" 
                                   required 
                                   style="border-radius: 10px; border: 2px solid #e8ecef;">
                        </div>

                        <div class="mb-3">
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

                        <div class="mb-3">
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
                            <small class="text-muted"><i class="fas fa-info-circle"></i> Minimum 6 characters</small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold" style="color: #2c3e50;">
                                <i class="fas fa-lock me-2"></i>Confirm Password
                            </label>
                            <input type="password" 
                                   name="confirm_password" 
                                   id="confirm_password" 
                                   class="form-control form-control-lg" 
                                   placeholder="••••••••" 
                                   required 
                                   style="border-radius: 10px; border: 2px solid #e8ecef;">
                        </div>

                        <button type="submit" 
                                name="register" 
                                class="btn btn-primary w-100 btn-lg mt-3" 
                                style="border-radius: 10px; padding: 15px; font-weight: 600;">
                            <i class="fas fa-user-plus me-2"></i>Create Account
                        </button>

                        <div class="text-center mt-4">
                            <p class="text-muted mb-0">
                                Already have an account? 
                                <a href="login.php" class="fw-bold" style="color: #3498db; text-decoration: none;">
                                    Login Here <i class="fas fa-arrow-right"></i>
                                </a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>


<script src="assets/js/auth-utils.js"></script>
<script>
document.querySelector('form').addEventListener('submit', function(e){
    let name = document.querySelector('input[name="name"]').value.trim();
    let email = document.querySelector('input[name="email"]').value.trim();
    let password = document.querySelector('input[name="password"]').value;
    let confirmPassword = document.querySelector('input[name="confirm_password"]').value;

    let error = "";

    if(name.length < 3){
        error = "Name must be at least 3 characters.";
    } else if(!/^\S+@\S+\.\S+$/.test(email)){
        error = "Invalid email address.";
    } else if(password.length < 6){
        error = "Password must be at least 6 characters.";
    } else if(password !== confirmPassword){
        error = "Passwords do not match.";
    }

    if(error){
        e.preventDefault();
        alert('⚠️ ' + error);
        return false;
    }
});
</script>

<?php include 'footer.php'; ?>