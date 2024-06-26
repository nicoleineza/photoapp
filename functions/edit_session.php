<?php
session_start();
include("../settings/config.php");

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['session_id'])) {
    $session_id = $_POST['session_id'];
    $new_session_name = $_POST['new_session_name'];
    $new_description = $_POST['new_description'];
    $new_date = $_POST['new_date'];
    $new_location = $_POST['new_location'];
    $new_price = $_POST['new_price'];

    $query = "UPDATE Sessions SET session_name=?, description=?, date=?, location=?, price=? WHERE session_id=?";
    $editsession = $connection->prepare($query);
       
        $editsession->bind_param("ssssdi", $new_session_name, $new_description, $new_date, $new_location, $new_price, $session_id);
   
        if ($editsession->execute()) {
            echo "Session updated successfully.";
        } else {
            echo "Error updating session: " . $stmt->error;
        }
        $stmt->close();
    } 
    

?>
