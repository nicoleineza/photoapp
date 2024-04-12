<?php
include ('../settings/config.php');

if(isset($_POST['username'], $_POST['email'], $_POST['password'], $_POST['user_type'])){
    $username = mysqli_real_escape_string($connection,$_POST['username']);
    $email = mysqli_real_escape_string($connection,$_POST['email']);
    $password = mysqli_real_escape_string($connection,$_POST['password']);
    $user_type = $_POST['user_type'];
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    
    // Check if username or email already exists
    $username_query = "SELECT * FROM Users WHERE username='$username'";
    $email_query = "SELECT * FROM Users WHERE email='$email'";
    $username_result = mysqli_query($connection, $username_query);
    $email_result = mysqli_query($connection, $email_query);
    
    if(mysqli_num_rows($username_result) > 0){
        echo "Username already exists!";
    }elseif(mysqli_num_rows($email_result) > 0){
        echo "Email already exists!";
    }else{
        $query = "INSERT INTO Users (username, email, password_hash, user_type) VALUES ('$username', '$email', '$password_hash', '$user_type')";
        if(mysqli_query($connection, $query)){
            echo "success"; 
        }else{
            echo "Error: " . mysqli_error($connection); 
        }
    }
}
?>
