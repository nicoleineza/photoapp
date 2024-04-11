<?php

include_once("../settings/config.php");


function fetchPhotographerPhotos($connection, $photographer_id) {
    $photos = array(); 

    $query = "SELECT * FROM Images WHERE photographer_id = ?";
    if ($image = $connection->prepare($query)) {
        // Bind parameters
        $image->bind_param("i", $photographer_id);
        // Execute the query
        $image->execute();
        // Get result
        $result = $image->get_result();
        
        // Fetch rows and populate photos array
        while ($row = $result->fetch_assoc()) {
            $photos[] = $row;
        }

        // Close statement
        $image->close();
    } else {
        // Print error message if the query preparation fails
        echo "Error preparing query: " . $connection->error;
    }

    // Return the array of photos
    return $photos;
}
?>
