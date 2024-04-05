<?php
session_start(); // Start session to access session variables

include("../settings/config.php");

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if user is not logged in
    header("Location: login.php");
    exit();
}

$photographer_id = $_SESSION['user_id'];

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize input data
    $session_name = trim($_POST["session_name"]);
    $description = trim($_POST["description"]);
    $date = trim($_POST["date"]);
    $location = trim($_POST["location"]);
    $price = trim($_POST["price"]);

    // Insert session into database
    $query = "INSERT INTO Sessions (photographer_id, session_name, description, date, location, price) VALUES (?, ?, ?, ?, ?, ?)";
    if ($stmt = $connection->prepare($query)) {
        // Bind parameters
        $stmt->bind_param("issssd", $photographer_id, $session_name, $description, $date, $location, $price);
        
        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // Redirect to sessions page with success message
            header("Location: ../views/photographer.php?success=1");
            exit();
        } else {
            // Redirect to sessions page with error message
            header("Location: ../views/photographer.php?error=1");
            exit();
        }

        // Close statement
        $stmt->close();
    }
}

// If form submission fails, redirect back to sessions page
header("Location: photographer.php");
exit();
?>