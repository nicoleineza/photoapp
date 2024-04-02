<?php
include("../settings/config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $bio_text = $_POST['bio_text'];
    $photographer_id = 1; // Assuming the photographer ID is known (replace with actual value)

    // Insert the bio into the database
    $sql = "INSERT INTO Photographer_Bio (photographer_id, bio_text) VALUES (?, ?)";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("is", $photographer_id, $bio_text);
    
    if ($stmt->execute()) {
        // Bio added successfully
        header("Location: photographer.php");
        exit();
    } else {
        // Error occurred
        echo "Error: " . $connection->error;
    }

    $stmt->close();
    $connection->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Bio</title>
    <link rel="stylesheet" href="../css/add_bio.css">
</head>
<body>
    <div class="container">
        <h2>Add Bio</h2>
        <form method="post">
            <div class="form-group">
                <label for="bio_text">Bio Text:</label><br>
                <textarea id="bio_text" name="bio_text" rows="4" cols="50" required></textarea>
            </div>
            <button type="submit">Submit</button>
        </form>
    </div>
</body>
</html>
