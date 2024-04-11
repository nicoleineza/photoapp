<?php
session_start(); 

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
    <title>Add New Session</title>
    <link rel="stylesheet" href="../css/picture.css">
    <link rel="icon" href="../assets/appicon.png">
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
                <li><a href="photographer.php?photographer"id="pdashboard"><i class="fas fa-plus-circle"></i>Create</a></li>

                <li><a href="pdashboard.php?page=pdashboard" id="pdashboard"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="portfolio.php?page=portfolio" id="portfolio"><i class="fas fa-camera"></i> Portfolios</a></li>
                <li><a href="sessions.php?page=sessions" id="sessions"><i class="fas fa-check-circle"></i> Sessions</a></li>
                <li><a href="profile.php?page=profile" id="profile"><i class="fas fa-user"></i> Profile</a></li>
                <li><a href="../login/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
</nav>
</nav>
<div id="content-wrapper">
        <h3> Add Session</h3>
        <form action="../functions/create_session.php" method="post">
            <label for="session_name">Session Name:</label><br>
            <input type="text" id="session_name" name="session_name" required><br>
            <label for="description">Description:</label><br>
            <textarea id="description" name="description" rows="4" cols="50" required></textarea><br>
            <label for="date">Date:</label><br>
            <input type="date" id="date" name="date" required><br>
            <label for="location">Location:</label><br>
            <input type="text" id="location" name="location" required><br>
            <label for="price">Price:</label><br>
            <input type="number" id="price" name="price" step="0.01" required><br>
            <input type="submit" value="Add Session">
        </form>
    </div>
</body>
</html>
