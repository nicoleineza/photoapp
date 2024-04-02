<?php
include ('../settings/config.php'); // Ensure this path is correct
session_start();

$email = mysqli_real_escape_string($connection, $_POST['email']);
$password = mysqli_real_escape_string($connection, $_POST['password']);

$query = "SELECT * FROM Users WHERE email = ?";
$stmt = $connection->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    $error_message = "Invalid email address";
    header("Location: ../login/login.php?error=$error_message");
    exit();
} else {
    $user = $result->fetch_assoc();
    if (!password_verify($password, $user['password_hash'])) {
        $error_message = "Wrong/Invalid Password";
        header("Location: ../login/login.php?error=$error_message");
        exit();
    } else {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['is_admin'] = $user['is_admin'];
        if ($user['user_type'] == 'photographer') {
            header("Location: ../views/photographer.php");
            exit();
        } else {
            header("Location: ../views/dashboard.php");
            exit();
        }
    }
}
?>
