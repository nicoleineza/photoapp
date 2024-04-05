<?php
// Include the database configuration
include_once('../settings/config.php');

// Check if the session ID is set
if(isset($_POST['session_id'])) {
    // Get the session ID from the POST data
    $session_id = $_POST['session_id'];
    
    // Assuming you have stored user ID in session, retrieve it
    session_start();
    $user_id = $_SESSION['user_id'];
    
    // Prepare and execute the SQL query to insert a new booking
    $sql = "INSERT INTO Bookings (session_id, user_id) VALUES ($session_id, $user_id)";
    if ($connection->query($sql) === TRUE) {
        // Booking successful
        echo "Session booked successfully!";
    } else {
        // Error occurred
        echo "Error: " . $sql . "<br>" . $connection->error;
    }
} else {
    // Session ID not set, handle error
    echo "Error: Session ID not provided.";
}

// Close the database connection
$connection->close();
?>
