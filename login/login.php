<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="icon" href="../assets/appicon.png">
    <link rel="stylesheet" href="../css/login.css">
</head>
<body>
<div class="container">
    <div class="login-header">
        
        <h2>LOGIN</h2>
    </div>
    <div class="login-box">
        <form action="../login/login_action.php" method="post" name="loginForm" id="loginForm" onsubmit="return validateForm()">
            <label for="username_or_email">Username or Email:</label>
            <input type="text" id="username_or_email" name="username_or_email" placeholder="Enter your username or email" required>
            <br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>
            <br>
            <button type="submit" id="signInButton" name="signInButton">Sign In</button>
        </form>
        <p><a href="signup.php">No account> click herer to Register</a></p>
        <?php 
        if (isset($_GET['error']) && !empty($_GET['error'])) {
            $error_message = $_GET['error'];
            echo '<p style="color: red;">' . $error_message . '</p>';
        }
        ?>
    </div>
</div>
</body>
</html>
