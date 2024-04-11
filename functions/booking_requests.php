<?php

include_once("../settings/config.php");
function requests($connection, $photographer_id) {
    $query = "SELECT Bookings.booking_id, Sessions.session_name, Sessions.date, Sessions.location, Sessions.price
              FROM Bookings
              INNER JOIN Sessions ON Bookings.session_id = Sessions.session_id
              WHERE Sessions.photographer_id = ? AND Bookings.status = 'pending'";

    $booking = $connection->prepare($query);
    
    $booking->bind_param("i", $photographer_id);

    $booking->execute();

    $result = $booking->get_result();

    $bookingRequests = array();
    while ($row = $result->fetch_assoc()) {
   
        $bookingRequests[] = $row;
    }
    $booking->close();
    return $bookingRequests;
}

?>