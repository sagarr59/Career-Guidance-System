<?php
include 'header_no_sidebar.php'; // This starts the session
include '../db.php';

// Redirect if already logged in
if(isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true){
    header("Location: index.php");
    exit;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!$username) $errors[] = "Username required";
    if (!$password) $errors[] = "Password required";

    if (empty($errors)) {
        // Use prepared statements for security
        $stmt = $conn->prepare("SELECT * FROM admins WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows == 1) {
            $row = $res->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_username'] = $username;
                header("Location: index.php");
                exit;
            } else {
                $errors[] = "Incorrect password";
            }
        } else {
            $errors[] = "Admin not found";
        }
        $stmt->close();
    }
}
?>

<div class="container d-flex align-items-center justify-content-center" style="min-height: 100vh;">
    <div class="card shadow-lg p-5" style="max-width: 450px; width: 100%;">
        <div class="text-center mb-4">
            <i class="fas fa-user-shield fa-4x text-primary mb-3"></i>
            <h2 class="fw-bold">Admin Login</h2>
            <p class="text-muted">Career Guidance System</p>
        </div>

        <?php foreach($errors as $e): ?>
            <div class='alert alert-danger'>
                <i class="fas fa-exclamation-circle me-2"></i><?= htmlspecialchars($e) ?>
            </div>
        <?php endforeach; ?>

        <form method="post" novalidate>
            <div class="mb-4">
                <label class="form-label fw-semibold">
                    <i class="fas fa-user me-2"></i>Username
                </label>
                <input type="text" name="username" class="form-control form-control-lg" placeholder="Enter admin username" required>
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">
                    <i class="fas fa-lock me-2"></i>Password
                </label>
                <input type="password" name="password" class="form-control form-control-lg" placeholder="Enter password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-lg w-100">
                <i class="fas fa-sign-in-alt me-2"></i>Login to Admin Panel
            </button>
        </form>
        
        <div class="text-center mt-4">
            <a href="../index.php" class="text-decoration-none">
                <i class="fas fa-home me-1"></i>Back to Home
            </a>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
