<?php
include_once("../settings/config.php");
// Start session
session_start();
include('../settings/auto.php');

try {
    // Check if user is logged in
    if(isset($_SESSION['user_id'])) {
        // Get user ID
        $userIdToDelete = $_SESSION['user_id'];

        // SQL query to delete the user
        $sql = "DELETE FROM Users WHERE user_id = :user_id";

        // Prepare the statement
        $stmt = $connection->prepare($sql);

        // Execute the query with parameters
        $stmt->execute(array(':user_id' => $userIdToDelete));

        // Destroy all sessions for the user
        session_unset();
        session_destroy();

        // Redirect to landing page
        header("Location: ../views/landing.php");
        exit(); // Stop further execution
    } else {
        echo "User not logged in.";
    }
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Close the connection (assuming $connection is your PDO connection)
$connection ->close();
?>
