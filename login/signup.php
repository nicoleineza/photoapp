<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Signup Page</title>
<link rel="stylesheet" href="../css/signup.css">
<link rel="icon" href="../assets/appicon.png">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
    // Function to check if passwords match
    function checkPasswordMatch() {
        var password = $("#password").val();
        var confirmPassword = $("#confirm_password").val();
        if (password !== confirmPassword) {
            $("#confirm_password_error").html("Passwords do not match!");
        } else {
            $("#confirm_password_error").html("");
        }
    }
    
    // Call checkPasswordMatch() on keyup event of confirm password field
    $("#confirm_password").keyup(checkPasswordMatch);
    
    $("#signupForm").submit(function(event){
        event.preventDefault(); // Prevent default form submission
        
        // Check if passwords match
        checkPasswordMatch();
        
        // Check if there are any error messages
        if ($("#confirm_password_error").html() !== "") {
            return; // Don't submit the form if there are errors
        }
        
        // Serialize form data
        var formData = $(this).serialize();
        
        // Send AJAX request
        $.ajax({
            url: '../functions/signup_action.php',
            type: 'post',
            data: formData,
            success: function(response){
                if(response.trim() === "success"){
                    // Clear form fields
                    $("#signupForm")[0].reset();
                    // Redirect to login page
                    window.location.replace("../login/login.php");
                    // Display success message
                    alert("Signed up successfully!");
                }else{
                    // Display error message
                    $("#signup_message").html(response);
                }
            }
        });
    });
    
    // AJAX for checking username
    $("#username").keyup(function(){
        var username = $(this).val().trim();
        if(username != ''){
            $.ajax({
                url: '../functions/check_username.php',
                type: 'post',
                data: {username: username},
                success: function(response){
                    $('#username_error').html(response);
                }
            });
        }else{
            $("#username_error").html("");
        }
    });

    // AJAX for checking email
    $("#email").keyup(function(){
        var email = $(this).val().trim();
        if(email != ''){
            $.ajax({
                url: '../functions/check_email.php',
                type: 'post',
                data: {email: email},
                success: function(response){
                    $('#email_error').html(response);
                }
            });
        }else{
            $("#email_error").html("");
        }
    });
});
</script>
</head>
<body>
<div class="signup-container">
    <h2>Signup</h2>
    <form id="signupForm" method="post">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <span id="username_error" class="error"></span>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <span id="email_error" class="error"></span>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div class="form-group">
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
            <span id="confirm_password_error" class="error"></span>
        </div>
        <div class="form-group">
            <label for="photographer">Are you a photographer?</label>
            <input type="radio" id="photographer" name="user_type" value="photographer" required>
            <label for="photographer">Yes</label>
            <input type="radio" id="non_photographer" name="user_type" value="non-photographer" required>
            <label for="non_photographer">No</label>
        </div>
        <button type="submit">Signup</button>
        <div id="signup_message"></div> 
        <p>><a href="login.php">have ana account? click here to login</a></p>
    </form>
</div>
</body>
</html>
