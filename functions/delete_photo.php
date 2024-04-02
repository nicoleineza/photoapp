<?php
session_start(); // Start session to access session variables

include("../settings/config.php");

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if user is not logged in
    header("Location: login.php");
    exit();
}

// Check if photo ID is provided
if (!isset($_POST['photo_id'])) {
    echo "Error: Photo ID not provided.";
    exit;
}

// Get the photo ID from the POST request
$photo_id = $_POST['photo_id'];

// Prepare and execute query to delete the photo from the database
$query = "DELETE FROM Photographs WHERE id = ?";
if ($stmt = $connection->prepare($query)) {
    // Bind parameters
    $stmt->bind_param("i", $photo_id);
    
    // Execute the query
    if ($stmt->execute()) {
        // Photo deleted successfully
        echo "Photo deleted successfully.";
    } else {
        // Error deleting photo
        echo "Error deleting photo.";
    }

    // Close statement
    $stmt->close();
} else {
    // Error preparing statement
    echo "Error preparing statement.";
}

// Close database connection
$connection->close();
?>
