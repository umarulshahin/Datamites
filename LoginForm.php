<?php
session_start();
$error_message = "";
if (isset($_SESSION['error_message'])) {
    $error_message = $_SESSION['error_message'];
    unset($_SESSION['error_message']);
} elseif (isset($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 100%;
            max-width: 400px;
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-header img {
            width: 80px;
            margin-bottom: 15px;
        }

        .form-control:focus {
            border-color: #6c63ff;
            box-shadow: 0 0 0 0.25rem rgba(108, 99, 255, 0.25);
        }

        .btn-primary {
            background-color: #6c63ff;
            border-color: #6c63ff;
            width: 100%;
            padding: 10px;
        }

        .btn-primary:hover {
            background-color: #5a52d3;
            border-color: #5a52d3;
        }

        .alert {
            margin-bottom: 20px;
        }

        .signup-link {
            text-align: center;
            margin-top: 15px;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-header">
            <h2>Welcome Back</h2>
            <p class="text-muted">Sign in to continue</p>
        </div>

        <?php if (isset($error_message) && !empty($error_message)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $error_message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>

        <?php endif; ?>
        
        <?php if (isset($success_message) && !empty($success_message)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $success_message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <form action="Login.php" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>

            </div>

            <button type="submit" value="Login" name="Login" class="btn btn-primary">Log In</button>
        </form>

        <div class="signup-link">
            <p>Don't have an account? <a href="signUpForm.php" class="text-decoration-none">Sign Up</a></p>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>