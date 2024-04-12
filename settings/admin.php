<?php
session_start();
$authCode = "1234"; 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $enteredCode = $_POST["code"];
    if ($enteredCode === $authCode) {
        $_SESSION["authenticated"] = true;
        header("Location: protected_page.php");
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
