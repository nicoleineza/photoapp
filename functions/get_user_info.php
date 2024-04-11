<?php
session_start();

include("../settings/auto.php");

$user_id = $_SESSION['user_id'];

include_once('../settings/config.php');
$sql = "SELECT username, email, user_type FROM Users WHERE user_id = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {

    $row = $result->fetch_assoc();

    $user_info = array(
        'username' => $row['username'],
        'email' => $row['email'],
        'user_type' => $row['user_type']
    );

    echo json_encode($user_info);
} else {
    echo json_encode(array('error' => 'User not found'));
}

$stmt->close();
$connection->close();
?>
