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
            <!-- Assuming the profile picture is stored in "profile-picture.jpg" -->
            <img src="profile-picture.jpg" alt="Profile Picture">
            <span class="username">John Doe</span>
        </div>
        <nav>
            <ul class="navigation-links">
                <li><a href="pdashboard.php"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="portfolio.php"><i class="fas fa-image"></i> Portfolios</a></li>
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

    <footer>
        <div class="footer-container">
            <div class="footer-links">
                <a href="#">About Us</a>
                <a href="#">Terms of Service</a>
                <a href="#">Privacy Policy</a>
                <a href="#">Contact</a>
            </div>
            <div class="social-icons">
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
    </footer>

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
