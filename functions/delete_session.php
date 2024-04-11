<?php
session_start();
include_once("../settings/config.php");


if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit();
}

// Check if session ID is provided
if (isset($_POST['sessionId'])) {
    $sessionId = $_POST['sessionId'];

    $deletesession = $connection->prepare("DELETE FROM Sessions WHERE session_id = ?");
    $deletesession->bind_param("i", $sessionId);

    if ($deletesession->execute()) {
     
        echo "Session deleted successfully.";
    } else {
       
        echo "Error deleting session. Please try again later.";
    }

    $deletesession->close();
} else {
    // Session ID not provided
    echo "Session ID not provided.";
}
?>
