<?php
include_once("../settings/config.php");
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["booking_id"]) && isset($_POST["status"])) {
        $booking_id = $_POST["booking_id"];
        $status = $_POST["status"];

        $query = "UPDATE Bookings SET status = ? WHERE booking_id = ?";
        $statement = $connection->prepare($query);
        $statement->bind_param("si", $status, $booking_id);
        
        if ($statement->execute()) {
            $_SESSION['flash_message'] = "Booking status updated successfully.";
        } else {
            $_SESSION['flash_message'] = "Error updating booking status: " . $statement->error;
        }
        $statement->close();
    } else {
        $_SESSION['flash_message'] = "Booking ID or status is missing.";
    }
    header("Location: " . $_SERVER["HTTP_REFERER"]);
    exit();
} 
?>
