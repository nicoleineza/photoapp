<?php
session_start(); // Start the session

// Database connection
include_once('../settings/config.php');

// Check if booking_id is set and the user is logged in
if (isset($_POST['booking_id']) && isset($_SESSION['user_id'])) {
    // Get booking_id and user_id
    $booking_id = $_POST['booking_id'];
    $user_id = $_SESSION['user_id'];

    // Prepare and execute delete statement
    $sql = "DELETE FROM Bookings WHERE booking_id = ? AND user_id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("ii", $booking_id, $user_id);

    if ($stmt->execute()) {
        // Booking deleted successfully
        echo "Booking deleted successfully.";
    } else {
        // Error occurred while deleting booking
        echo "Error deleting booking: " . $connection->error;
    }

    // Close prepared statement
    $stmt->close();
} else {
    // If booking_id or user_id is not set
    echo "Invalid request.";
}

// Close database connection
$connection->close();
?>
