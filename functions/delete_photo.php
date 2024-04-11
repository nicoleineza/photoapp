<?php
session_start();
include("../settings/config.php");

if (!isset($_SESSION['user_id'])) {

    http_response_code(401);
    exit();
}
if (!isset($_POST['photoId']) || !ctype_digit($_POST['photoId'])) {
    http_response_code(400);
    exit();
}

$photoId = $_POST['photoId'];

$query = "DELETE FROM Images WHERE id = ? AND photographer_id = ?";
$deletephoto = $connection->prepare($query);
  
    $deletephoto->bind_param("ii", $photoId, $_SESSION['user_id']);
 
    if ($deletephoto->execute()) {
        echo "Photo deleted successfully";
    } else {
        http_response_code(500);
        echo "Error deleting photo: " . $connection->error;
    }
    $deletephoto->close();

$connection->close();
?>
