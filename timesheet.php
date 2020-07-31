<?php
include 'config.php' ;

if(isset($_GET['api_key']) && $_GET['api_key'] == $api_key) {
    $action = $_GET['action'];
    switch($action) {
        case 'getTasks':
            $taskQuery = "SELECT t.id, t.title, t.hours FROM dates AS d JOIN tasks AS t ON d.id = t.date_id WHERE date LIKE '" . $_GET['date'] . "'";
            $result = mysqli_query($conn, $taskQuery);
            $response = [];
            if ($result->num_rows > 0) {
                while($row = mysqli_fetch_assoc($result)) {
                    $response[] = $row;
                }
            }
            
            echo json_encode($response);
            break;
        case 'addTask':
            $dateQuery = "SELECT id FROM dates WHERE date LIKE '" . $_GET['date'] . "'";
            $result = mysqli_query($conn, $dateQuery);
            if ($result->num_rows > 0) {
                while($row = mysqli_fetch_assoc($result)) {
                    $dateID = $row['id'];
                }
                $hoursQuery = "SELECT SUM(hours) AS total_hours from tasks WHERE date_id = " . $dateID;
                $hours = mysqli_query($conn, $hoursQuery);
                $totalHours = mysqli_fetch_assoc($hours);
                if (($totalHours['total_hours'] + $_GET['hours']) > $allowedHours) {
                    echo json_encode(false);
                } else {
                    $insertQuery = "INSERT INTO tasks VALUES (NULL, ".$dateID.", '".$_GET['title']."', ".$_GET['hours'].", now(), NULL)";
                    mysqli_query($conn, $insertQuery);
                    $taskId = mysqli_insert_id($conn);
                    echo json_encode($taskId);
                }
            } else {
                $insertQuery = "INSERT INTO dates VALUES (NULL, ".$_GET['date'].")";
                mysqli_query($conn, $insertQuery);
                $dateID = mysqli_insert_id($conn);
                $insertQuery = "INSERT INTO tasks VALUES (NULL, ".$dateID.", '".$_GET['title']."', ".$_GET['hours'].", now(), NULL)";
                mysqli_query($conn, $insertQuery);
                $taskId = mysqli_insert_id($conn);
                echo json_encode($taskId);
            }
            break;
        case 'editTask' :
            $hoursQuery = "SELECT SUM(hours) AS total_hours from tasks join dates ON tasks.date_id = dates.id WHERE dates.date LIKE '" . $_GET['date'] . "' AND tasks.id <> " . $_GET['id'];
            $hours = mysqli_query($conn, $hoursQuery);
            $totalHours = mysqli_fetch_assoc($hours);
            if (($totalHours['total_hours'] + $_GET['hours']) > $allowedHours) {
                echo json_encode(false);
            } else {
                $updateQuery = "UPDATE tasks SET title = '" . $_GET['title'] . "', hours = '" . $_GET['hours'] . "' WHERE id = " . $_GET['id'];
                mysqli_query($conn, $updateQuery);
                echo true;
            }
            break;
        case 'deleteTask':
            $deleteQuery = "DELETE FROM tasks WHERE id = " . $_GET['id'];
            mysqli_query($conn, $deleteQuery);
            echo json_encode(true);
        break;
    }
}
?>