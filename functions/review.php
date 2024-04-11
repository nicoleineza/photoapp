<?php


include_once("../settings/config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    session_start();

    $reviewer_id = $_SESSION['user_id'];

    $photographer_id = filter_input(INPUT_POST, 'photographer_id', FILTER_VALIDATE_INT);
    $image_id = filter_input(INPUT_POST, 'image_id', FILTER_VALIDATE_INT);
    $rating = filter_input(INPUT_POST, 'rating', FILTER_VALIDATE_INT);
    $comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if (($rating !== false || !empty($comment)) && $photographer_id !== false && $image_id !== false) {

        $sql = "INSERT INTO Reviews (reviewer_id, photographer_id, image_id, rating, comment)
                VALUES (?, ?, ?, ?, ?)";
        $review = $connection->prepare($sql);
        $review->bind_param("iiiss", $reviewer_id, $photographer_id, $image_id, $rating, $comment);

        // Execute the statement
        $success = $review->execute();

        if ($success) {
            echo "Review added successfully!";
        } else {
            echo "Error adding review. Please try again later.";
    
        }
    } else {
        echo "Invalid input data. Please make sure at least one of the fields (rating or comment) is filled correctly.";
    }
}
?>
