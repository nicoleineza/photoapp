<?php

session_start(); // Start the session

include("../settings/auto.php");
// Retrieve user ID from session
$user_id = $_SESSION['user_id'];

// Database connection
include_once('../settings/config.php');

// Retrieve user's information
$sql_user_info = "SELECT * FROM Users WHERE user_id = ?";
$stmt_user_info = $connection->prepare($sql_user_info);
$stmt_user_info->bind_param("i", $user_id);
$stmt_user_info->execute();
$result_user_info = $stmt_user_info->get_result();

if ($result_user_info->num_rows > 0) {
    $user_info = $result_user_info->fetch_assoc();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="../css/profile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<header>
    <div class="user-profile">
    <h1><img src="../assets/profile.png" alt="Profile Picture" style="width: 50px; height: 50px; border-radius: 50%;"> <?php echo $user_info['username']; ?>!</h1>
    </div>
    <nav>
        <ul class="navigation-links">
            <li><a href="pdashboard.php"><i class="fas fa-home"></i> Home</a></li>
            <li><a href="portfolio.php"><i class="fas fa-home"></i> Portfolios</a></li>
            <li><a href="profile.php"><i class="fas fa-user"></i> Profile</a></li>
            <li><a href="settings.php"><i class="fas fa-cog"></i> Settings</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </nav>
</header>

    <div class="edit-profile-container">
        <h2>Edit Profile</h2>
        <div class="edit-section">
            <p>Existing Username: <span id="existing_username"></span> <button id="editUsername">Edit</button></p>
            <div id="edit_username" class="edit-form" style="display: none;">
                <form id="editUsernameForm" method="post">
                    <input type="text" id="new_username" name="new_username" required>
                    <span id="username_availability" class="availability"></span> <!-- Display username availability -->
                    <button type="submit">Save</button>
                </form>
            </div>
        </div>
        <div class="edit-section">
            <p>Existing Email: <span id="existing_email"></span> <button id="editEmail">Edit</button></p>
            <div id="edit_email" class="edit-form" style="display: none;">
                <form id="editEmailForm" method="post">
                    <input type="email" id="new_email" name="new_email" required>
                    <span id="email_availability" class="availability"></span> <!-- Display email availability -->
                    <button type="submit">Save</button>
                </form>
            </div>
        </div>
        <div class="edit-section">
            <p>Existing User Type: <span id="existing_user_type"></span> <button id="editUserType">Edit</button></p>
            <div id="edit_user_type" class="edit-form" style="display: none;">
                <form id="editUserTypeForm" method="post">
                    <select id="new_user_type" name="new_user_type" required>
                        <option value="photographer">Photographer</option>
                        <option value="non-photographer">Non-Photographer</option>
                    </select>
                    <button type="submit">Save</button>
                </form>
            </div>
        </div>
        <div class="edit-section">
            <p>Change Password: <button id="editPassword">Edit</button></p>
            <div id="edit_password" class="edit-form" style="display: none;">
                <form id="editPasswordForm" method="post">
                    <div class="form-group">
                        <label for="old_password">Old Password:</label>
                        <input type="password" id="old_password" name="old_password" required>
                    </div>
                    <div class="form-group">
                        <label for="new_password">New Password:</label>
                        <input type="password" id="new_password" name="new_password">
                    </div>
                    <div class="form-group">
                        <label for="confirm_new_password">Confirm New Password:</label>
                        <input type="password" id="confirm_new_password" name="confirm_new_password">
                        <span id="confirm_password_error" class="error"></span>
                    </div>
                    <button type="submit">Save</button>
                </form>
            </div>
        </div>
        <div id="edit_profile_message"></div> <!-- Message container -->
        <div class="delete-account">
            <button id="deleteUser">Delete Account</button>
        </div>
    </div>


    <!-- Add the confirmation message modal (initially hidden) -->
    <div id="deleteConfirmationModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p>Are you sure you want to delete your account? This action cannot be undone.</p>
            <button id="confirmDelete">Yes, Delete</button>
            <button id="cancelDelete">Cancel</button>
            <div id="deletionStatusMessage"></div>
        </div>
    </div>

</body>
</html>
