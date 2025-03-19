<?php
session_start();
include("Connect.php");

// Check if user is logged in
if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
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

// Handle Edit User Action
if (isset($_POST['edit_user'])) {
    $edit_id = sanitize($_POST['edit_id']);
    $edit_username = sanitize($_POST['edit_username']);
    $edit_email = sanitize($_POST['edit_email']);
    $edit_phone = sanitize($_POST['edit_phone']);
    $edit_description = sanitize($_POST['edit_description']);
    
    // Update user info
    $update_query = "UPDATE `users details` SET username = ?, email = ?, phone = ?, description = ? WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ssssi", $edit_username, $edit_email, $edit_phone, $edit_description, $edit_id);
    
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "User updated successfully";
    } else {
        $_SESSION['error_message'] = "Error updating user: " . $conn->error;
    }
    
    header("Location: dashboard.php");
    exit();
}

// Handle Delete User Action
if (isset($_GET['delete_id']) && !empty($_GET['delete_id'])) {
    $delete_id = sanitize($_GET['delete_id']);
    
    // Delete user
    $delete_query = "DELETE FROM `users details` WHERE id = ?";
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

// Get user profile image
$profile_query = "SELECT profile FROM `users details` WHERE username = ?";
$stmt = $conn->prepare($profile_query);
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($profile);
$stmt->fetch();
$stmt->close();
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
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #6c63ff;">
        <div class="container">
            <h1><a class="navbar-brand" style="font-size: large;" href="dashboard.php">User Dashboard</a></h1>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle header-profile" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php if (!empty($profile)): ?>
                                <img src="<?php echo $profile; ?>" alt="Profile" class="profile-img me-2">
                            <?php else: ?>
                                <i class="fas fa-user-circle fa-2x me-2 text-white"></i>
                            <?php endif; ?>
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
                <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error_message']) && !empty($_SESSION['error_message'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
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
                            <?php if ($result && $result->num_rows > 0): ?>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td>
                                            <?php if (!empty($row['profile'])): ?>
                                                <img src="<?php echo $row['profile']; ?>" alt="Profile" class="table-img">
                                            <?php else: ?>
                                                <i class="fas fa-user-circle fa-2x text-secondary"></i>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo $row['username']; ?></td>
                                        <td><?php echo $row['email']; ?></td>
                                        <td><?php echo $row['phone']; ?></td>
                                        <td><?php echo (strlen($row['description']) > 50) ? substr($row['description'], 0, 50) . '...' : $row['description']; ?></td>
                                        <td class="action-buttons">
                                            <a href="#" class="btn btn-sm btn-primary me-1" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $row['id']; ?>">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <a href="#" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo $row['id']; ?>">
                                                <i class="fas fa-trash-alt"></i> Delete
                                            </a>
                                        </td>
                                    </tr>
                                    
                                    <!-- Edit Modal for each user -->
                                    <div class="modal fade" id="editModal<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editModalLabel">Edit User</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="dashboard.php" method="post">
                                                    <div class="modal-body">
                                                        <input type="hidden" name="edit_id" value="<?php echo $row['id']; ?>">
                                                        
                                                        <div class="mb-3">
                                                            <label for="edit_username<?php echo $row['id']; ?>" class="form-label">Username</label>
                                                            <input type="text" class="form-control" id="edit_username<?php echo $row['id']; ?>" name="edit_username" value="<?php echo $row['username']; ?>" required>
                                                        </div>
                                                        
                                                        <div class="mb-3">
                                                            <label for="edit_email<?php echo $row['id']; ?>" class="form-label">Email</label>
                                                            <input type="email" class="form-control" id="edit_email<?php echo $row['id']; ?>" name="edit_email" value="<?php echo $row['email']; ?>" required>
                                                        </div>
                                                        
                                                        <div class="mb-3">
                                                            <label for="edit_phone<?php echo $row['id']; ?>" class="form-label">Phone</label>
                                                            <input type="text" class="form-control" id="edit_phone<?php echo $row['id']; ?>" name="edit_phone" value="<?php echo $row['phone']; ?>" required>
                                                        </div>
                                                        
                                                        <div class="mb-3">
                                                            <label for="edit_description<?php echo $row['id']; ?>" class="form-label">Description</label>
                                                            <textarea class="form-control" id="edit_description<?php echo $row['id']; ?>" name="edit_description" rows="3"><?php echo $row['description']; ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" name="edit_user" class="btn btn-primary">Save Changes</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Delete Modal for each user -->
                                    <div class="modal fade" id="deleteModal<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Are you sure you want to delete user <strong><?php echo $row['username']; ?></strong>? This action cannot be undone.</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <a href="dashboard.php?delete_id=<?php echo $row['id']; ?>" class="btn btn-danger">Delete</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="6" class="text-center">No users found</td></tr>
                            <?php endif; ?>
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