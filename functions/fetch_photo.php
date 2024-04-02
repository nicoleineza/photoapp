<?php
session_start(); // Start session to access session variables

include("../settings/config.php");

// Check if user is logged in and get the photographer ID from session
if (isset($_SESSION['user_id'])) {
    $photographer_id = $_SESSION['user_id'];
} else {
    // Redirect to login page if user is not logged in
    header("Location: login.php");
    exit();
}

// Fetch photo URLs from the Photographs table for the logged-in photographer
$sql = "SELECT image_url FROM Photographs WHERE photographer_id = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("i", $photographer_id);
$stmt->execute();
$result = $stmt->get_result();
$photos = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>
