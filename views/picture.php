<?php
session_start(); // Start the session

// Include necessary files

include_once "../settings/config.php";

// Check if the user is logged in and is a photographer
if(!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'photographer') {
    // Redirect non-photographer users to a different page or show an error message
    header("Location: ../index.php");
    exit();
}

// Retrieve user ID from session
$user_id = $_SESSION['user_id'];

// Retrieve user's information
$sql_user_info = "SELECT * FROM Users WHERE user_id = ?";
$stmt_user_info = $connection->prepare($sql_user_info);
$stmt_user_info->bind_param("i", $user_id);
$stmt_user_info->execute();
$result_user_info = $stmt_user_info->get_result();

$user_info = [];
if ($result_user_info->num_rows > 0) {
    $user_info = $result_user_info->fetch_assoc();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Picture</title>
    <link rel="icon" href="../assets/appicon.png">
    <link rel="stylesheet" href="../css/picture.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
</head>
<body>
<nav class="side-nav">
<nav class="side-nav">
    <div class="user-profile">
        <img src="../assets/profile.png" alt="Profile Picture" class="profile-picture">
        <p class="username"><?php echo $user_info['username']; ?></p>
    </div>
    <ul>
                <li> <a href="photographer.php?photographer"id="pdashboard"><i class="fas fa-plus-circle"></i>Create</a></li>

                <li><a href="pdashboard.php?page=pdashboard" id="pdashboard"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="portfolio.php?page=portfolio" id="portfolio"><i class="fas fa-camera"></i> Portfolios</a></li>
                <li><a href="sessions.php?page=sessions" id="sessions"><i class="fas fa-check-circle"></i> Sessions</a></li>
                <li><a href="profile.php?page=profile" id="profile"><i class="fas fa-user"></i> Profile</a></li>
                <li><a href="../login/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
</nav>
</nav>

<div id="content-wrapper">
    <h2>ADD PICTURE</h2>
    <form id="productForm" action="../functions/upload_picture.php" method="post" enctype="multipart/form-data">
        <label for="productName">Picture Description:</label><br>
        <input type="text" id="productName" name="productName" required><br><br>

        <label for="productImage">Choose Image from Files to Upload:</label><br>
        <input type="file" id="productImage" name="productImage" accept="image/*" required><br><br>

        <label for="isForSale">Do you intend to sell this image?</label><br>
        <input type="radio" id="yes" name="isForSale" value="1" required onclick="togglePriceInput()">
        <label for="yes">Yes</label>
        <input type="radio" id="no" name="isForSale" value="0" onclick="togglePriceInput()">
        <label for="no">No</label><br><br>

        <div id="priceInput" style="display:none;">
            <label for="productPrice">Enter Price for Your Image:</label><br>
            <input type="number" id="productPrice" name="productPrice" step="0.01" min="0"><br><br>
        </div>

        <?php
        if (isset($_SESSION['user_id'])) {
            echo '<input type="hidden" name="photographerId" value="' . $_SESSION['user_id'] . '">';
        } else {
            echo '<p>Please log in to upload your new picture</p>';
        }
        ?>

        <input type="submit" value="Submit">
    </form>
</div>

<script>
function togglePriceInput() {
    var isForSale = document.getElementById("yes").checked;
    var priceInput = document.getElementById("priceInput");
    if (isForSale) {
        priceInput.style.display = "block";
    } else {
        priceInput.style.display = "none";
    }
}
</script>

</body>
</html>
