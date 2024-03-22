<?php
include ('../settings/config.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PhotoApp</title>
</head>
<body>
    <h1>Welcome to the Farmers Market</h1>

    <?php
    // Query to retrieve produce data
    $sql = "SELECT Name, Quantity, Price FROM Produce";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Output data of each row
        echo "<h2>Available Produce:</h2>";
        echo "<ul>";
        while ($row = $result->fetch_assoc()) {
            echo "<li>" . $row["Name"] . " - Quantity: " . $row["Quantity"] . ", Price: $" . $row["Price"] . "</li>";
        }
        echo "</ul>";
    } else {
        echo "No produce available at the moment.";
    }

    $conn->close();
    ?>

</body>
</html>
