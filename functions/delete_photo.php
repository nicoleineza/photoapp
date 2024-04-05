<?php
session_start();
include("../settings/config.php");

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // If not logged in, return unauthorized response
    http_response_code(401);
    exit();
}

// Check if photoId is set and is a positive integer
if (!isset($_POST['photoId']) || !ctype_digit($_POST['photoId'])) {
    // If photoId is missing or not a positive integer, return bad request response
    http_response_code(400);
    exit();
}

$photoId = $_POST['photoId'];

// Prepare and execute query to delete the photo from the database
$query = "DELETE FROM Images WHERE id = ? AND photographer_id = ?";
if ($stmt = $connection->prepare($query)) {
    // Bind parameters
    $stmt->bind_param("ii", $photoId, $_SESSION['user_id']);
    // Execute the query
    if ($stmt->execute()) {
        // Deletion successful
        echo "Photo deleted successfully";
    } else {
        // Error occurred during deletion
        http_response_code(500);
        echo "Error deleting photo: " . $connection->error;
    }
    // Close statement
    $stmt->close();
} else {
    // Error preparing query
    http_response_code(500);
    echo "Error preparing query: " . $connection->error;
}

// Close database connection
$connection->close();
?>
