<?php
function fetchPhotographerSessions($connection, $photographer_id) {
    $sessions = array(); 

    $query = "SELECT * FROM Sessions WHERE photographer_id = ?";
    if ($data = $connection->prepare($query)) {
        $data->bind_param("i", $photographer_id);
        $data->execute();
        $result = $data->get_result();
        while ($row = $result->fetch_assoc()) {
            $sessions[] = $row;
        }
        $data->close();
    } 
    return $sessions;
}
?>
