<?php

include_once("../settings/config.php");


//function to fetch images for a given photographer
function fetchPhotographerPhotos($connection, $photographer_id) {
    $photos = array(); 

    $query = "SELECT * FROM Images WHERE photographer_id = ?";
    if ($image = $connection->prepare($query)) {
       
        $image->bind_param("i", $photographer_id);
       
        $image->execute();
    
        $result = $image->get_result();
        //create an array to store the fetched images and their corresponding details
        while ($row = $result->fetch_assoc()) {
            $photos[] = $row;
        }

        // Close statement
        $image->close();
    } else {
        echo "Error preparing query: " . $connection->error;
    }
    return $photos;
}
?>
