<?php
session_start();

// Predefined authentication code
$authCode = "1234"; // Change this to your desired authentication code

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the entered code from the form
    $enteredCode = $_POST["code"];

    // Validate the entered code
    if ($enteredCode === $authCode) {
        // Authentication successful, set session variable
        $_SESSION["authenticated"] = true;
        header("Location: protected_page.php"); // Redirect to the protected page
        exit();
    } else {
        $error = "Invalid code. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authentication</title>
</head>
<body>
    <h2>Enter Authentication Code</h2>
    <form method="post">
        <input type="text" name="code" placeholder="Enter code" required>
        <button type="submit">Submit</button>
    </form>
    <?php if (isset($error)) echo "<p>$error</p>"; ?>
</body>
</html>
