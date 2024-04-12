<?php
include ("../settings/config.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit();
}


