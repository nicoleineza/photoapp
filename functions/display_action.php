<?php
include("../settings/config.php");

// Fetch any picture from the database
$sql = "SELECT content, type FROM pictures LIMIT 1"; // Fetch the first picture found in the database
$stmt = $connection->prepare($sql);
$stmt->execute();
$stmt->bind_result($content, $type);
$stmt->fetch();
$stmt->close();

// Set header and output the image
header("Content-type: $type");
echo $content;
?>
