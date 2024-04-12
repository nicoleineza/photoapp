<?php
session_start();

$_SESSION['flash_message'] = "You've been logged out";

$_SESSION = array();
session_destroy();
header("Location: ../login/login.php");
exit();
?>
