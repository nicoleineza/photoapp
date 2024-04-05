<?php
session_start();
include("../settings/config.php");

// Function to sanitize input data
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Function to validate if the logged-in user is the owner of the photo
function validateOwnership($connection, $photo_id, $user_id) {
    $query = "SELECT * FROM Images WHERE id = ? AND photographer_id = ?";
    if ($stmt = $connection->prepare($query)) {
        $stmt->bind_param("ii", $photo_id, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $photo = $result->fetch_assoc();
        $stmt->close();
        return $photo;
    } else {
        return false;
    }
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "You are not logged in.";
    exit();
}

$user_id = $_SESSION['user_id'];

// Check if the form data is received via POST request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['editPhotoId'])) {
    $photo_id = sanitize($_POST['editPhotoId']);

    // Validate if the logged-in user is the owner of the photo
    $photo = validateOwnership($connection, $photo_id, $user_id);

    if (!$photo) {
        echo "You are not authorized to edit this photo.";
        exit();
    }

    // Handle edit description
    if (isset($_POST['editDescription'])) {
        $description = sanitize($_POST['editDescription']);

        // Update picture description in the database
        $query = "UPDATE Images SET productName = ? WHERE id = ?";
        if ($stmt = $connection->prepare($query)) {
            $stmt->bind_param("si", $description, $photo_id);
            if ($stmt->execute()) {
                echo "Picture description updated successfully.";
            } else {
                echo "Error updating picture description.";
            }
            $stmt->close();
        } else {
            echo "Error preparing update statement.";
        }
    }

    // Handle edit sale status
    if (isset($_POST['editIsForSale'])) {
        $is_for_sale = sanitize($_POST['editIsForSale']);

        // Update sale status in the database
        $query = "UPDATE Images SET isForSale = ? WHERE id = ?";
        if ($stmt = $connection->prepare($query)) {
            $stmt->bind_param("ii", $is_for_sale, $photo_id);
            if ($stmt->execute()) {
                
            } else {
                echo "Error updating sale status.";
            }
            $stmt->close();
        } else {
            echo "Error preparing update statement.";
        }
    }

    // Handle edit price
    if (isset($_POST['editPrice'])) {
        $price = sanitize($_POST['editPrice']);

        // Update price in the database
        $query = "UPDATE Images SET productPrice = ? WHERE id = ?";
        if ($stmt = $connection->prepare($query)) {
            $stmt->bind_param("di", $price, $photo_id);
            if ($stmt->execute()) {
                
            } else {
                echo "Error updating price.";
            }
            $stmt->close();
        } else {
            echo "Error preparing update statement.";
        }
    }

    // Handle other actions as needed

} else {
    echo "Invalid request.";
}
?>
