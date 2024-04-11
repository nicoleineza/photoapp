<?php

include_once("../settings/config.php");
function requests($connection, $photographer_id) {
    // Prepare SQL query to fetch booking requests with status = pending
    $query = "SELECT Bookings.booking_id, Sessions.session_name, Sessions.date, Sessions.location, Sessions.price
              FROM Bookings
              INNER JOIN Sessions ON Bookings.session_id = Sessions.session_id
              WHERE Sessions.photographer_id = ? AND Bookings.status = 'pending'";

    // Prepare the statement
    $booking = $connection->prepare($query);
    
    $booking->bind_param("i", $photographer_id);

    // Execute the statement
    $booking->execute();

    // Get the result set
    $result = $booking->get_result();

    // Initialize an empty array to store booking requests
    $bookingRequests = array();

    // Fetch each row from the result set
    while ($row = $result->fetch_assoc()) {
        // Add the row to the array
        $bookingRequests[] = $row;
    }

    // Close the statement
    $booking->close();

    // Return the array of booking requests
    return $bookingRequests;
}

?>