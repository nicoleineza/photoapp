<?php
define('DB_HOST', 'g9fej9rujq0yt0cd.cbetxkdyhwsb.us-east-1.rds.amazonaws.com');
define('DB_USER', 'rw5k3fwiketxzlnq');
define('DB_PASSWORD', 'q9rrky2sg0wazoe7'); 
define('DB_NAME', 'lm6hn94qfjqgz8j0');


$connection = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
} 


?>
