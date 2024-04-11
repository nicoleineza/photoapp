<?php
session_start(); // Start session to access session variables

include("../settings/config.php");

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if user is not logged in
    header("Location: login.php");
    exit();
}

// Get the user ID of the logged-in user
$user_id = $_SESSION['user_id'];

// Check if file was uploaded without errors
if (isset($_FILES["file"]) && $_FILES["file"]["error"] == 0) {
    $filename = $_FILES["file"]["name"];
    $tempname = $_FILES["file"]["tmp_name"];
    $filetype = $_FILES["file"]["type"];
    $filesize = $_FILES["file"]["size"];

    // Read the file content
    $filecontent = file_get_contents($tempname);

    // Prepare and execute SQL statement to insert the file into the database
    $stmt = $connection->prepare("INSERT INTO Photographs (name, type, size, content, photographer_id) VALUES (?, ?, ?, ?, ?)");

    if (!$stmt) {
        // Error preparing statement
        http_response_code(500);
        exit();
    }

    // Bind parameters
    $stmt->bind_param("ssisi", $filename, $filetype, $filesize, $filecontent, $user_id);

    // Execute statement
    if (!$stmt->execute()) {
        // Error executing statement
        http_response_code(500);
        exit();
    }

    // Close statement
    $stmt->close();

    // Redirect back to photographer.php with success message
    header("Location: ../views/photographer.php?success=1");
    exit();
} else {
    // Error: File upload failed
    http_response_code(500);
    exit();
}
?>
