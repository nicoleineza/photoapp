<?php
session_start(); // Start the session

// Include database connection
include_once("../settings/config.php");

// Check if form data is provided
if(isset($_POST['action'])) {
    $action = $_POST['action'];
    
    // Check if user is logged in
    if(isset($_SESSION['user_id'])) {
        $userId = $_SESSION['user_id'];

        switch ($action) {
            case 'update_username':
                if(isset($_POST['new_username'])) {
                    $newUsername = mysqli_real_escape_string($connection, $_POST['new_username']);

                    // Check if the new username already exists
                    $query = "SELECT COUNT(*) AS count FROM Users WHERE username = '$newUsername'";
                    $result = mysqli_query($connection, $query);
                    $row = mysqli_fetch_assoc($result);
                    if ($row['count'] > 0) {
                        echo "Username already exists. Please choose a different one.";
                        exit;
                    }

                    $sql = "UPDATE Users SET username = '$newUsername' WHERE user_id = '$userId'";
                    $message = "success"; // Return success directly
                } else {
                    $message = "New username not provided!";
                }
                break;
            
            case 'update_email':
                if(isset($_POST['new_email'])) {
                    $newEmail = mysqli_real_escape_string($connection, $_POST['new_email']);

                    // Check if the new email already exists
                    $query = "SELECT COUNT(*) AS count FROM Users WHERE email = '$newEmail'";
                    $result = mysqli_query($connection, $query);
                    $row = mysqli_fetch_assoc($result);
                    if ($row['count'] > 0) {
                        echo "Email already exists. Please choose a different one.";
                        exit;
                    }

                    $sql = "UPDATE Users SET email = '$newEmail' WHERE user_id = '$userId'";
                    $message = "success"; // Return success directly
                } else {
                    $message = "New email not provided!";
                }
                break;

            case 'update_password':
                if(isset($_POST['old_password']) && isset($_POST['new_password'])) {
                    $oldPassword = mysqli_real_escape_string($connection, $_POST['old_password']);
                    $newPassword = mysqli_real_escape_string($connection, $_POST['new_password']);

                    // Get user's current password hash from the database
                    $sql = "SELECT password_hash FROM Users WHERE user_id = '$userId'";
                    $result = mysqli_query($connection, $sql);
                    if($result) {
                        $row = mysqli_fetch_assoc($result);
                        $currentPasswordHash = $row['password_hash'];

                        // Verify old password
                        if(password_verify($oldPassword, $currentPasswordHash)) {
                            // Hash and update new password
                            $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
                            $sql = "UPDATE Users SET password_hash = '$newPasswordHash' WHERE user_id = '$userId'";
                            $message = "success"; // Return success directly
                        } else {
                            $message = "Old password is incorrect!";
                        }
                    } else {
                        $message = "Error querying the database: " . mysqli_error($connection);
                    }
                } else {
                    $message = "Old or new password not provided!";
                }
                break;

            case 'update_user_type':
                if(isset($_POST['new_user_type'])) {
                    $newUserType = mysqli_real_escape_string($connection, $_POST['new_user_type']);
                    $sql = "UPDATE Users SET user_type = '$newUserType' WHERE user_id = '$userId'";
                    $message = "success"; // Return success directly
                } else {
                    $message = "New user type not provided!";
                }
                break;
            
            default:
                $message = "Invalid action!";
                break;
        }

        // Execute SQL query if action is valid
        if(isset($sql)) {
            if(mysqli_query($connection, $sql)) {
                echo $message;
            } else {
                // Log error
                error_log("Error updating profile: " . mysqli_error($connection));
                echo "Error updating profile. Please try again later.";
            }
        } else {
            echo $message;
        }
    } else {
        echo "User not logged in!";
    }
} else {
    echo "Form data not provided!";
}
?>
