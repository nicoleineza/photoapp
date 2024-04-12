<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup Page</title>
    <link rel="stylesheet" href="../css/signup.css">
    <link rel="icon" href="../assets/appicon.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="../js/signup.js"></script>
    <style>
        .app-icon {
            width: 50px;
            height: auto;
            position: absolute;
            top: 20px;
            left: 20px;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>
<div class="header">
    <img src="../assets/appicon.png" alt="App Icon" class="app-icon">
    <h2>PhotoApp</h2>
</div>
<div class="signup-container">
    <h2>Register to get Started!</h2>
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
            <span id="password_error" class="error"></span>
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
        <p><a href="login.php">Have an account? Click here to login</a></p>
    </form>
</div>
<script>
    $(document).ready(function () {
        $('#signupForm').submit(function (event) {
            // Validate email
            var email = $('#email').val();
            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                $('#email_error').text('Please enter a valid email address.');
                event.preventDefault();
            } else {
                $('#email_error').text('');
            }

            // Validate password
            var password = $('#password').val();
            var passwordRegex = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*]).{8,}$/;
            if (!passwordRegex.test(password)) {
                $('#password_error').text('Password must contain at least 8 characters, including one uppercase letter, one lowercase letter, one number, and one special character.');
                event.preventDefault();
            } else {
                $('#password_error').text('');
            }

            // Confirm password
            var confirmPassword = $('#confirm_password').val();
            if (password !== confirmPassword) {
                $('#confirm_password_error').text('Passwords do not match.');
                event.preventDefault();
            } else {
                $('#confirm_password_error').text('');
            }
        });
    });
</script>
</body>
</html>
