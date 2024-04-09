<?php
include_once("../settings/config.php");

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate the presence of booking ID and status
    if (isset($_POST["booking_id"]) && isset($_POST["status"])) {
        // Get the booking ID and status from the form submission
        $booking_id = $_POST["booking_id"];
        $status = $_POST["status"];

        // Perform any additional validation if necessary

        // Update the booking status in the database
        $query = "UPDATE Bookings SET status = ? WHERE booking_id = ?";
        $statement = $connection->prepare($query);
        $statement->bind_param("si", $status, $booking_id);
        
        if ($statement->execute()) {
            // Booking status updated successfully
            echo "Booking status updated successfully.";
            
        } else {
            // Error updating booking status
            echo "Error updating booking status: " . $statement->error;
        }

        // Close the statement
        $statement->close();
    } else {
        // Booking ID or status is missing
        echo "Booking ID or status is missing.";
    }
} else {
    // Request method is not POST
    echo "Invalid request method.";
}
?>
