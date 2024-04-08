$(document).ready(function() {
    // Fetch user information on page load
    $.ajax({
        url: '../functions/get_user_info.php',
        type: 'post',
        success: function(response){
            var userInfo = JSON.parse(response);
            $("#existing_username").text(userInfo.username);
            $("#existing_email").text(userInfo.email);
            $("#existing_user_type").text(userInfo.user_type);
        },
        error: function(xhr, status, error) {
            console.error("Error fetching user information:", error);
        }
    });
    
    // Function to check if passwords match
    function checkPasswordMatch() {
        var newPassword = $("#new_password").val();
        var confirmNewPassword = $("#confirm_new_password").val();
        if (newPassword !== confirmNewPassword) {
            $("#confirm_password_error").html("Passwords do not match!");
        } else {
            $("#confirm_password_error").html("");
        }
    }
    
    // Call checkPasswordMatch() on keyup event of confirm new password field
    $("#confirm_new_password").keyup(checkPasswordMatch);
    
    $("#editUsername").click(function() {
        $("#edit_username").show();
    });

    $("#editEmail").click(function() {
        $("#edit_email").show();
    });

    $("#editPassword").click(function() {
        $("#edit_password").show();
    });

    // Function to check username availability
    function checkUsernameAvailability() {
        var newUsername = $("#new_username").val().trim();
        if (newUsername !== '') {
            $.ajax({
                url: '../functions/check_username.php',
                type: 'post',
                data: {username: newUsername},
                success: function(response){
                    $('#username_availability').html(response);
                }
            });
        }
    }

    // Call checkUsernameAvailability() on keyup event of new username field
    $("#new_username").keyup(checkUsernameAvailability);

    // Function to check email availability
    function checkEmailAvailability() {
        var newEmail = $("#new_email").val().trim();
        if (newEmail !== '') {
            $.ajax({
                url: '../functions/check_email.php',
                type: 'post',
                data: {email: newEmail},
                success: function(response){
                    $('#email_availability').html(response);
                }
            });
        }
    }

    // Call checkEmailAvailability() on keyup event of new email field
    $("#new_email").keyup(checkEmailAvailability);

    $("#editUsernameForm").submit(function(event){
        event.preventDefault(); // Prevent default form submission
        
        // Check if username is available
        checkUsernameAvailability();

        // Other form submission logic...
        
        // Serialize form data
        var formData = $(this).serialize();
        
        // Send AJAX request
        $.ajax({
            url: '../functions/update_profile.php',
            type: 'post',
            data: formData + '&action=update_username', // Include action parameter
            success: function(response){
                if(response.trim() === "success"){
                    // Clear form fields
                    $("#editUsernameForm")[0].reset();
                    // Hide messages after 5 seconds
                    setTimeout(function() {
                        $("#edit_profile_message").html("");
                    }, 5000);
                    // Reload the page after changes are saved
                    setTimeout(function() {
                        location.reload();
                    }, 5000);
                    // Display success message
                    $("#edit_profile_message").html("Username updated successfully!");
                }else{
                    // Display error message
                    $("#edit_profile_message").html(response);
                }
            },
            error: function(xhr, status, error) {
                console.error("Error updating username:", error);
                // Display error message
                $("#edit_profile_message").html("Error updating username. Please try again later.");
            }
        });
    });

    $("#editEmailForm").submit(function(event){
        event.preventDefault(); // Prevent default form submission
        
        // Check if email is available
        checkEmailAvailability();

        // Other form submission logic...
        
        // Serialize form data
        var formData = $(this).serialize();
        
        // Send AJAX request
        $.ajax({
            url: '../functions/update_profile.php',
            type: 'post',
            data: formData + '&action=update_email', // Include action parameter
            success: function(response){
                if(response.trim() === "success"){
                    // Clear form fields
                    $("#editEmailForm")[0].reset();
                    // Hide messages after 5 seconds
                    setTimeout(function() {
                        $("#edit_profile_message").html("");
                    }, 5000);
                    // Reload the page after changes are saved
                    setTimeout(function() {
                        location.reload();
                    }, 5000);
                    // Display success message
                    $("#edit_profile_message").html("Email updated successfully!");
                }else{
                    // Display error message
                    $("#edit_profile_message").html(response);
                }
            },
            error: function(xhr, status, error) {
                console.error("Error updating email:", error);
                // Display error message
                $("#edit_profile_message").html("Error updating email. Please try again later.");
            }
        });
    });

    $('#deleteUser').click(function() {
        $('#deleteConfirmationModal').show();
    });

    // Close the modal when the user clicks on the "x" or "Cancel" button
    $('.close, #cancelDelete').click(function() {
        $('#deleteConfirmationModal').hide();
    });

    // Handle deletion when the user confirms
    $('#confirmDelete').click(function() {
        // Send AJAX request to delete the account
        $.ajax({
            url: '../functions/delete_account.php', // Replace with the actual server-side endpoint to handle user deletion
            type: 'POST',
            success: function(response) {
                // Update the message container with the success message
                $('#deletionStatusMessage').text('Your account has been deleted successfully.');
                // Hide the modal after successful deletion
                $('#deleteConfirmationModal').hide();
                // Redirect to landing page or perform any other action after deletion
                window.location.href = 'landing.php'; // Replace 'landing_page.php' with the actual landing page URL
            },
            error: function(xhr, status, error) {
                // Update the message container with the error message
                $('#deletionStatusMessage').text('An error occurred while deleting your account. Please try again later.');
                console.error(error);
            }
        });
    });
    // Other form submission functions go here...

});
