<?php
include_once("../settings/config.php");

function fetchAllPhotosWithPhotographerNames($connection) {
    $photos = array(); 

    $query = "SELECT i.*, u.username AS photographer_name FROM Images i JOIN Users u ON i.photographer_id = u.user_id";
    if ($result = $connection->query($query)) {
        while ($row = $result->fetch_assoc()) {
            $photos[] = $row;
        }
        $result->free();
    } else {
        echo "Error executing query: " . $connection->error;
    }
    return $photos;
}
