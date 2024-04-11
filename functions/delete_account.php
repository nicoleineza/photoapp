<?php
include_once("../settings/config.php");
session_start();
include('../settings/auto.php');

try {

    if(isset($_SESSION['user_id'])) {
        $userIdToDelete = $_SESSION['user_id'];
        $sql = "DELETE FROM Users WHERE user_id = :user_id";

        $delete = $connection->prepare($sql);

        // Execute the query with parameters
        $delete->execute(array(':user_id' => $userIdToDelete));

        session_unset();
        session_destroy();

        header("Location: ../login/logout.php");
        exit(); // 
    } else {
        echo "User not logged in.";
    }
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$connection ->close();
?>
