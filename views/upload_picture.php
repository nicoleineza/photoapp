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
$photographer_id = $_SESSION['user_id'];

// Function to sanitize inputs
function sanitizeInput($input) {
    // Remove tags and encode special characters
    $input = htmlspecialchars(strip_tags($input));
    return $input;
}

// Sanitize and validate input
$productName = isset($_POST["productName"]) ? sanitizeInput($_POST["productName"]) : '';
$productPrice = isset($_POST["productPrice"]) ? floatval($_POST["productPrice"]) : 0;
$isForSale = isset($_POST["isForSale"]) ? ($_POST["isForSale"] == "1" ? 1 : 0) : 0;

// Check if file was uploaded without errors
if (isset($_FILES["productImage"]) && $_FILES["productImage"]["error"] == 0) {
    $filename = $_FILES["productImage"]["name"];
    $tempname = $_FILES["productImage"]["tmp_name"];
    $filetype = $_FILES["productImage"]["type"];
    $filesize = $_FILES["productImage"]["size"];

    // Read the file content
    $filecontent = file_get_contents($tempname);

    // Prepare and execute SQL statement to insert the file into the database
    $stmt = $connection->prepare("INSERT INTO Images (productName, productImage, isForSale, productPrice, photographer_id) VALUES (?, ?, ?, ?, ?)");

    if (!$stmt) {
        // Error preparing statement
        http_response_code(500);
        exit();
    }

    // Bind parameters
    $stmt->bind_param("ssidi", $productName, $filecontent, $isForSale, $productPrice, $photographer_id);

    // Execute statement
    if (!$stmt->execute()) {
        // Error executing statement
        http_response_code(500);
        exit();
    }

    // Close statement
    $stmt->close();

    // Success: File uploaded successfully
    echo "Product uploaded successfully";
    exit();
} else {
    // Error: File upload failed
    http_response_code(500);
    exit();
}
?>
