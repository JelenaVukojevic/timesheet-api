<?php
include 'config.php' ;

if(isset($_GET['date'])) {
    $taskQuery = "SELECT * FROM dates JOIN tasks ON dates.id = tasks.date_id WHERE date LIKE '" . $_GET['date'] . "'";
    
    $result = $conn->query($taskQuery);

    $response = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $response[] = $row;
        }
    }
    
    echo json_encode($response);
}
?>