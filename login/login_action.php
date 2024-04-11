<?php
include ('../settings/config.php'); // Ensure this path is correct
session_start();

$username_or_email = mysqli_real_escape_string($connection, $_POST['username_or_email']);
$password = mysqli_real_escape_string($connection, $_POST['password']);

// Check if the input is an email address
if (filter_var($username_or_email, FILTER_VALIDATE_EMAIL)) {
    $query = "SELECT * FROM Users WHERE email = ?";
} else {
    $query = "SELECT * FROM Users WHERE username = ?";
}

$stmt = $connection->prepare($query);
$stmt->bind_param("s", $username_or_email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    $error_message = "Invalid username or email address";
    header("Location: ../login/login.php?error=$error_message");
    exit();
} else {
    $user = $result->fetch_assoc();
    if (!password_verify($password, $user['password_hash'])) {
        $error_message = "Invalid password";
        header("Location: ../login/login.php?error=$error_message");
        exit();
    } else {
        // Start the session and set session variables
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username']; // or any other user information you want to store
        $_SESSION['email'] = $user['email'];
        $_SESSION['user_type'] = $user['user_type'];

        // Redirect to appropriate page based on user type
        if ($user['user_type'] == 'photographer') {
            header("Location: ../views/photographer.php");
            exit();
        } else {
            header("Location: ../views/pdashboard.php");
            exit();
        }
    }
}
?>
