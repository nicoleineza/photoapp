<?php
//this function checks and informs the user if the username they want to signup with doesnot arleady exist
include('../settings/config.php');

if(isset($_POST['email'])){
    $email = $_POST['email'];
    $query = "SELECT * FROM Users WHERE email='$email'";
    $result = mysqli_query($connection, $query);
    if(mysqli_num_rows($result) > 0){
        echo "<span class='error'>Email already exists!</span>";
    }else{
        echo "<span class='success'>Email available!</span>";
    }
}
?>
