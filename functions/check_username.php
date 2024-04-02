<?php
include('../settings/config.php');

if(isset($_POST['username'])){
    $username = $_POST['username'];
    $query = "SELECT * FROM Users WHERE username='$username'";
    $result = mysqli_query($connection, $query);
    if(mysqli_num_rows($result) > 0){
        echo "<span class='error'>Username already exists!</span>";
    }else{
        echo "<span class='success'>Username available!</span>";
    }
}
?>
