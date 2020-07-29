<?php
include 'config.php' ;

if(isset($_GET['date'])) {
    $taskQuery = "SELECT * FROM dates JOIN tasks ON dates.id = tasks.date_id WHERE date LIKE '" . $_GET['date'] . "'";
    var_dump($taskQuery);
}
?>