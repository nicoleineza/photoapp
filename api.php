<?php
// Include your database configuration
include 'config.php';

// Set headers to allow CORS (Cross-Origin Resource Sharing)
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Check if CustomerID is provided in the request
if (isset($_GET['customer_id'])) {
    // Get the provided CustomerID
    $customer_id = $_GET['customer_id'];

    // Prepare SQL statement
    $sql = "SELECT * FROM Produce";

    // Execute the SQL query
    $result = $connection->query($sql);

    if ($result === false) {
        // Query execution failed
        echo json_encode(array('error' => $connection->error));
    } else {
        if ($result->num_rows > 0) {
            // Customer exists
            $data=array();
            while($row=$result->fetch_assoc()){
                $data[]=$row;
            }

            echo json_encode($data);
        } else {
            // Customer does not exist
            echo json_encode(array('message' => 'No produce available'));
        }
    }
} else {
    // No CustomerID provided
    echo json_encode(array('error' => 'Please provide CustomerID'));
}

// Close the database connection
$connection->close();
?>
