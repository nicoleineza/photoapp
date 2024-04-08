<?php
include ("../settings/config.php");


// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect user to login page or display a message
    header("Location: ../login/login.php");
    exit();
}


