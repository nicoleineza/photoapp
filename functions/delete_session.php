<?php
session_start(); // Start session to access session variables
include_once("../settings/config.php");

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if user is not logged in
    header("Location: ../login/login.php");
    exit();
}

// Check if session ID is provided
if (isset($_POST['sessionId'])) {
    $sessionId = $_POST['sessionId'];

    // Prepare and execute the SQL DELETE statement
    $stmt = $connection->prepare("DELETE FROM Sessions WHERE session_id = ?");
    $stmt->bind_param("i", $sessionId);

    if ($stmt->execute()) {
        // Session deleted successfully
        echo "Session deleted successfully.";
    } else {
        // Error occurred while deleting session
        echo "Error deleting session. Please try again later.";
    }

    $stmt->close();
} else {
    // Session ID not provided
    echo "Session ID not provided.";
}
?>
