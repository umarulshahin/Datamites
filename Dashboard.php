<?php
session_start();
include("Connect.php");

// Check if user is logged in
if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    // Redirect to login page if not logged in
    $_SESSION['error_message'] = "Please login to access the dashboard";
    header("Location: index.php");
    exit();
}

// Get current user info
$username = $_SESSION['username'];



// Function to sanitize input
function sanitize($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Handle Delete User Action
if (isset($_GET['delete_id']) && !empty($_GET['delete_id'])) {
    $delete_id = sanitize($_GET['delete_id']);
    
    // Delete user
    $delete_query = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $delete_id);
    
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "User deleted successfully";
    } else {
        $_SESSION['error_message'] = "Error deleting user: " . $conn->error;
    }
    
    // Redirect to refresh the page
    header("Location: dashboard.php");
    exit();
}

// Get all users
$user_query = "SELECT id, username, email, phone, profile, description FROM `users details`";
$result = $conn->query($user_query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .profile-img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }
        
        .table-img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
        }
        
        .action-buttons .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
        
        .welcome-text {
            margin-bottom: 0;
            margin-right: 15px;
        }
        
        .header-profile {
            display: flex;
            align-items: center;
        }
        .dropdown-item:hover{
            background-color: red;
            color: white;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-dark " style="background-color: #6c63ff;">
        <div class="container">
            <h1><a class="navbar-brand" style="font-size: large;" href="dashboard.php">User Dashboard</a></h1>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle header-profile" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php
                            // Get user profile image
                            $profile_query = "SELECT profile FROM `users details` WHERE username = ?";
                            $stmt = $conn->prepare($profile_query);
                            $stmt->bind_param("s", $username);
                            $stmt->execute();
                            $stmt->bind_result($profile);
                            $stmt->fetch();
                            $stmt->close();
                            
                            if (!empty($profile)) {
                                echo '<img src="'. $profile . '" alt="Profile" class="profile-img me-2">';
                            
                            } else {
                                echo '<i class="fas fa-user-circle fa-2x me-2 text-white"></i>';
                            }
                            ?>
                            <span class="welcome-text text-white">Welcome, <?php echo $username; ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuLink">
                         
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="Logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-4">
        <div class="row mb-4">
            <div class="col">
                <div class="d-flex justify-content-between align-items-center">
                    <h2>User Management</h2>
                </div>
            </div>
        </div>
        
        <?php if (isset($_SESSION['success_message']) && !empty($_SESSION['success_message'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php 
                echo $_SESSION['success_message']; 
                unset($_SESSION['success_message']);
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error_message']) && !empty($_SESSION['error_message'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php 
                echo $_SESSION['error_message']; 
                unset($_SESSION['error_message']);
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Profile</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result && $result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo '<tr>';
                                    echo '<td>';
                                    if (!empty($row['profile'])) {
                                        echo '<img src="'. $row['profile'] . '" alt="Profile" class="table-img">';
                                    } else {
                                        echo '<i class="fas fa-user-circle fa-2x text-secondary"></i>';
                                    }
                                    echo '</td>';
                                    echo '<td>' . $row['username'] . '</td>';
                                    echo '<td>' . $row['email'] . '</td>';
                                    echo '<td>' . $row['phone'] . '</td>';
                                    echo '<td>' . (strlen($row['description']) > 50 ? substr($row['description'], 0, 50) . '...' : $row['description']) . '</td>';
                                    echo '<td class="action-buttons">';
                                    echo '<a href="edit_user.php?id=' . $row['id'] . '" class="btn btn-sm btn-primary me-1"><i class="fas fa-edit"></i> Edit</a>';
                                    echo '<a href="#" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal' . $row['id'] . '"><i class="fas fa-trash-alt"></i> Delete</a>';
                                    echo '</td>';
                                    echo '</tr>';
                                    
                                    // Delete Modal for each user
                                    echo '<div class="modal fade" id="deleteModal' . $row['id'] . '" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">';
                                    echo '<div class="modal-dialog">';
                                    echo '<div class="modal-content">';
                                    echo '<div class="modal-header">';
                                    echo '<h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>';
                                    echo '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
                                    echo '</div>';
                                    echo '<div class="modal-body">';
                                    echo '<p>Are you sure you want to delete user <strong>' . $row['username'] . '</strong>? This action cannot be undone.</p>';
                                    echo '</div>';
                                    echo '<div class="modal-footer">';
                                    echo '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>';
                                    echo '<a href="dashboard.php?delete_id=' . $row['id'] . '" class="btn btn-danger">Delete</a>';
                                    echo '</div>';
                                    echo '</div>';
                                    echo '</div>';
                                    echo '</div>';
                                }
                            } else {
                                echo '<tr><td colspan="6" class="text-center">No users found</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
<?php
// Close connection
$conn->close();
?>