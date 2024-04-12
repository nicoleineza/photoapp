<?php
session_start();

include("../settings/config.php");
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$photographer_id = $_SESSION['user_id'];

// Function to sanitize inputs
function sanitizeInput($input) {
    $input = htmlspecialchars(strip_tags($input));
    return $input;
}

if (isset($_FILES["productImage"]) && $_FILES["productImage"]["error"] == 0) {

    $productName = isset($_POST["productName"]) ? sanitizeInput($_POST["productName"]) : '';
    $productPrice = isset($_POST["productPrice"]) ? floatval($_POST["productPrice"]) : 0;
    $isForSale = isset($_POST["isForSale"]) ? ($_POST["isForSale"] == "1" ? 1 : 0) : 0;
    $filecontent = file_get_contents($_FILES["productImage"]["tmp_name"]);
    $stmt = $connection->prepare("INSERT INTO Images (productName, productImage, isForSale, productPrice, photographer_id) VALUES (?, ?, ?, ?, ?)");

    if (!$stmt) {
        http_response_code(500);
        exit();
    }
    $stmt->bind_param("ssidi", $productName, $filecontent, $isForSale, $productPrice, $photographer_id);

    if ($stmt->execute()) {
        // Set success message in session
        $_SESSION['flash_message'] = 'File uploaded successfully!';
        
        $stmt->close();
        header("Location: ../views/photographer.php");
        exit();
    } else {
        http_response_code(500);
        exit();
    }
} else {
    http_response_code(500);
    exit();
}
?>
