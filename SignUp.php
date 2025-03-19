<?php

session_start();

include("Connect.php");

if (isset($_POST['SignUp'])) {

    $username = $_POST['username'];
    $mobile = $_POST['mobile'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $image = $_FILES['image']['name'];
    $description = $_POST['description'];
    $error_message = "";

    $email_check = "SELECT * FROM `users details` WHERE email = '$email'";
    $phone_check = "SELECT * FROM `users details` WHERE phone = '$mobile'";
    $email_result = $conn->query($email_check);
    $phone_result = $conn->query($phone_check);


    if (empty($username) || empty($mobile) || empty($email) || empty($password) || empty($confirm_password) || empty($image) || empty($description)) {
        $error_message = "All fields are required";
    } elseif ($password !== $confirm_password) {

        $error_message = "Passwords do not match";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error_message = "Invalid email format";
    } elseif ($email_result->num_rows > 0) {

        $error_message = "Email Address already Exists !";
    } elseif ($phone_result->num_rows > 0) {
        $error_message = "Phone number already Exists !";
    } else {

        if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
            $image = $_FILES['image']['name'];

            $upload_dir = "uploads/";
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $image_path = $upload_dir . time() . '_' . $image;

            if (move_uploaded_file($_FILES["image"]["tmp_name"], $image_path)) {
                echo "Image uploaded: $image<br>";
            } else {
                $error_message = "Failed to move uploaded file";
            }
        } else {

            $error_message = "Profile image is required";
        }

        if (!empty($image_path)) {

            $hash_password = password_hash($password, PASSWORD_DEFAULT);

            $insertquery = "INSERT INTO `users details` (username, phone, email, password, profile, description) 
                   VALUES (?, ?, ?, ?, ?, ?)";

            $value = $conn->prepare($insertquery);
            $value->bind_param("ssssss", $username, $mobile, $email, $hash_password, $image_path, $description);

            if ($value->execute()) {
                $_SESSION['success_message'] = "Registration successful! Please log in.";
                header("location: LoginForm.php");
                exit();
            } else {
                $error_message = "Error:" . $conn->error;
            }
        }
    }


    if (!empty($error_message)) {
        $_SESSION['error_message'] = $error_message;
        header("location: SignUpForm.php");
        echo "Error: $error_message";

        exit();
    }
}
