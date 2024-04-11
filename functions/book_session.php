<?php
include_once('../settings/config.php');

if(isset($_POST['session_id'])) {

    $session_id = $_POST['session_id'];
    session_start();
    $user_id = $_SESSION['user_id'];

    $sql = "INSERT INTO Bookings (session_id, user_id) VALUES ($session_id, $user_id)";
    if ($connection->query($sql) === TRUE) {

    } else {

        echo "Error: " . $sql . "<br>" . $connection->error;
    }
} else {

    echo "Error: Session ID not provided.";
}
$connection->close();
?>
