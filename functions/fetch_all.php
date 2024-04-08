<?php
include_once("../settings/config.php");

function fetchAllPhotosWithPhotographerNames($connection) {
    $photos = array(); // Initialize an empty array to store photos

    // Prepare and execute query to fetch all photos with photographer names
    $query = "SELECT i.*, u.username AS photographer_name FROM Images i JOIN Users u ON i.photographer_id = u.user_id";
    if ($result = $connection->query($query)) {
        // Fetch rows and populate photos array
        while ($row = $result->fetch_assoc()) {
            $photos[] = $row;
        }
        // Free result set
        $result->free();
    } else {
        // Print error message if the query execution fails
        echo "Error executing query: " . $connection->error;
    }

    // Return the array of photos
    return $photos;
}
