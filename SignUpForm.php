
<?php
session_start();
$error_message = "";
if(isset($_SESSION['error_message'])) {
    $error_message = $_SESSION['error_message'];
    unset($_SESSION['error_message']); 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px 0;
        }
        .signup-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 100%;
            max-width: 600px;
        }
        .signup-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .signup-header img {
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
        .image-preview {
            width: 150px;
            height: 150px;
            border: 2px dashed #dee2e6;
            border-radius: 5px;
            margin: 0 auto 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        .image-preview img {
            max-width: 100%;
            max-height: 100%;
            display: none;
        }
        .login-link {
            text-align: center;
            margin-top: 15px;
        }
        .alert {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <div class="signup-container">
        <div class="signup-header">
            <h2>Create Account</h2>
            <p class="text-muted">Join our community today</p>
        </div>
        
        <?php if(isset($error_message) && !empty($error_message)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo $error_message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>
        
        <form action="SignUp.php" method="POST" enctype="multipart/form-data">
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="mobile" class="form-label">Mobile Number</label>
                    <input type="tel" class="form-control" id="mobile" name="mobile" required>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control" id="email" name="email" required>
                <div class="form-text">This will be your unique identifier</div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="confirm_password" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                </div>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Profile Image</label>
                <div class="image-preview" id="imagePreview">
                    <img src="" alt="Image Preview" id="preview">
                    <span id="textPreview">Profile Image</span>
                </div>
                <input type="file" class="form-control" id="image" name="image" accept="image/*">
            </div>
            
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3" placeholder="Tell us a bit about yourself"></textarea>
            </div>
       
            
            <button type="submit" value="SignUp" name="SignUp" class="btn btn-primary">Sign Up</button>
        
        </form>
        
        <div class="login-link">
            <p>Already have an account? <a href="Login.html" class="text-decoration-none">Log In</a></p>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Script for image preview -->
    <script>
        document.getElementById('image').addEventListener('change', function(e) {
            const preview = document.getElementById('preview');
            const textPreview = document.getElementById('textPreview');
            const file = e.target.files[0];
            
            if (file) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    textPreview.style.display = 'none';
                }
                
                reader.readAsDataURL(file);
            } else {
                preview.style.display = 'none';
                textPreview.style.display = 'block';
            }
        });
    </script>
</body>
</html>