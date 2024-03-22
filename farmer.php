<?php
include ('../settings/config.php');
// Query to fetch produce data
$sql = "SELECT Name, Quantity, Price FROM Produce";
$result = $connection->query($sql);

if ($result->num_rows > 0) {
  
  echo "<h2>Available Produce:</h2>";
  echo "<table border='1'>
        <tr>
          <th>Name</th>
          <th>Quantity</th>
          <th>Price</th>
        </tr>";
  while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . $row["Name"] . "</td>";
    echo "<td>" . $row["Quantity"] . "</td>";
    echo "<td>$" . $row["Price"] . "</td>";
    echo "</tr>";
  }
  echo "</table>";
} else {
  echo "No produce available at the moment.";
}

$connection->close();
?>
