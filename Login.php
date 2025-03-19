
<?php

session_start(); 
  
include("Connect.php");

if (isset($_POST['Login'])){

    $username = $_POST['username'];
    $password = $_POST['password'];
    $error_message = "";

    if (empty($username) || empty($password)) {
        $error_message = "Username and password are required";
    } else {
     
        $sql = "SELECT * FROM `users details` WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows==1){
          $user = $result->fetch_assoc();

          echo "username : {$user['username']}password : {$user['password']}";

          var_dump($user['password']);
          if (password_verify($password, $user['password'])){

            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: Dashboard.php");

          }else{
            echo " yes it is woring";
            $error_message = "Invalid username or password";

          }

        }else{
            echo " yes it is also woring";

            $error_message = "Invalid username or password";
        }
    
    }

    if (!empty($error_message)) {
        $_SESSION['error_message'] = $error_message;
        header("Location: LoginForm.php");
        echo "Error: $error_message";

        exit();
    }

}
?>