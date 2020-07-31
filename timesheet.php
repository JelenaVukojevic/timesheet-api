<?php
    include 'config.php' ;
    include 'databaseClass.php';
    include 'timesheetClass.php';


    if(isset($_GET['api_key']) && $_GET['api_key'] == $api_key) {
        $database = new Database($host, $database, $username, $password);
        $timesheet = new Timesheet($database, $allowedHours);
        
        $action = $_GET['action'];
        switch($action) {
            case 'getTasks':
                $timesheet->getTasks();
            break;
            case 'addTask':
                $timesheet->addTask();
            break;
            case 'editTask':
                $timesheet->editTask();
            break;
            case 'deleteTask':
                $timesheet->deleteTask();
            break;
        }
    }
?>