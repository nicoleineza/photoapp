<?php


include_once("../settings/config.php");

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    session_start(); // Start the session to access session variables

    // Retrieve the logged-in user's ID from the session
    $reviewer_id = $_SESSION['user_id'];

    // Validate and sanitize form inputs
    $photographer_id = filter_input(INPUT_POST, 'photographer_id', FILTER_VALIDATE_INT);
    $image_id = filter_input(INPUT_POST, 'image_id', FILTER_VALIDATE_INT);
    $rating = filter_input(INPUT_POST, 'rating', FILTER_VALIDATE_INT);
    $comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // Check if either rating or comment is provided
    if (($rating !== false || !empty($comment)) && $photographer_id !== false && $image_id !== false) {
        // Insert the review into the database
        $sql = "INSERT INTO Reviews (reviewer_id, photographer_id, image_id, rating, comment)
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $connection->prepare($sql);

        // Bind parameters
        $stmt->bind_param("iiiss", $reviewer_id, $photographer_id, $image_id, $rating, $comment);

        // Execute the statement
        $success = $stmt->execute();

        // Check if the insertion was successful
        if ($success) {
            echo "Review added successfully!";
        } else {
            echo "Error adding review. Please try again later.";
            // Optionally, you can also output the specific error message for debugging:
            // echo "Error: " . $stmt->error;
        }
    } else {
        echo "Invalid input data. Please make sure at least one of the fields (rating or comment) is filled correctly.";
    }
}
?>
