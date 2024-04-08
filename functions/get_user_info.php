<?php
// Start the session to access session variables
session_start();

include("../settings/auto.php");

$user_id = $_SESSION['user_id'];

// Include your database connection file
include_once('../settings/config.php');

// Assume $conn is your database connection variable

// Prepare and execute the SQL query to fetch user information
$sql = "SELECT username, email, user_type FROM Users WHERE user_id = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if the query was successful
if ($result->num_rows > 0) {
    // Fetch the user information
    $row = $result->fetch_assoc();

    // Store user information in an associative array
    $user_info = array(
        'username' => $row['username'],
        'email' => $row['email'],
        'user_type' => $row['user_type']
    );

    // Encode the user information as JSON and output it
    echo json_encode($user_info);
} else {
    // No user found with the given user ID
    echo json_encode(array('error' => 'User not found'));
}

// Close the database connection
$stmt->close();
$connection->close();
?>
