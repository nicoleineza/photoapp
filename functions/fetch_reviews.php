<?php
include_once("../settings/config.php");
//code to fetch reviews for a given image
function fetchReviewsForImage($connection, $image_id) {
    $query = "SELECT * FROM Reviews WHERE image_id = ?";
    $statement = $connection->prepare($query);
    $statement->bind_param("i", $image_id);
    $statement->execute();
    
    // Get the result
    $result = $statement->get_result();
    
    // Fetch reviews as  array
    $reviews = [];
    while ($row = $result->fetch_assoc()) {
        $reviews[] = $row;
    }
    
    // Close the statement
    $statement->close();
    
    return $reviews;
}
?>
