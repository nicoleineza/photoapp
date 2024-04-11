<?php
session_start(); 
include_once('../settings/config.php');

if (isset($_POST['booking_id']) && isset($_SESSION['user_id'])) {
    $booking_id = $_POST['booking_id'];
    $user_id = $_SESSION['user_id'];

    $sql = "DELETE FROM Bookings WHERE booking_id = ? AND user_id = ?";
    $removebooking = $connection->prepare($sql);
    $removebooking->bind_param("ii", $booking_id, $user_id);

    if ($removebooking->execute()) {
        echo "Booking deleted successfully.";
    } else {
        echo "Error deleting booking: " . $connection->error;
    }

    $removebooking->close();
} else {
    echo "Invalid request.";
}

$connection->close();
?>
