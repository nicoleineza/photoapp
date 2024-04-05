<?php
include("../settings/config.php");

// Check if file was uploaded without errors
if (isset($_FILES["file"]) && $_FILES["file"]["error"] == 0) {
    $filename = $_FILES["file"]["name"];
    $tempname = $_FILES["file"]["tmp_name"];
    $filetype = $_FILES["file"]["type"];
    $filesize = $_FILES["file"]["size"];
    $description = isset($_POST['description']) ? $_POST['description'] : null; // Get description from form

    // Read the file content
    $filecontent = file_get_contents($tempname);

    // Prepare and execute SQL statement to insert the file into the database
    $stmt = $connection->prepare("INSERT INTO Photographs (name, type, size, content, photographer_id, description) VALUES (?, ?, ?, ?, ?, ?)");

    if (!$stmt) {
        die("Error preparing statement: " . $connection->error);
    }

    // Bind parameters
    $stmt->bind_param("sssiss", $filename, $filetype, $filesize, $filecontent, $photographer_id, $description);

    // Assuming $photographer_id is set somewhere in your code
    $photographer_id = $_SESSION['user_id']; // or however you retrieve it

    // Execute statement
    if (!$stmt->execute()) {
        die("Error executing statement: " . $stmt->error);
    }

    // Close statement
    $stmt->close();

    echo "File uploaded successfully.";
} else {
    // Handle file upload errors
    switch ($_FILES["file"]["error"]) {
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            echo "The uploaded file exceeds the maximum file size limit.";
            break;
        case UPLOAD_ERR_PARTIAL:
            echo "The uploaded file was only partially uploaded.";
            break;
        case UPLOAD_ERR_NO_FILE:
            echo "No file was uploaded.";
            break;
        case UPLOAD_ERR_NO_TMP_DIR:
            echo "Missing temporary folder.";
            break;
        case UPLOAD_ERR_CANT_WRITE:
            echo "Failed to write file to disk.";
            break;
        case UPLOAD_ERR_EXTENSION:
            echo "A PHP extension stopped the file upload.";
            break;
        default:
            echo "Unknown error occurred during file upload.";
            break;
    }
}
?>
