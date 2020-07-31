<?php
    class Timesheet
    {
        protected $database;
        protected $allowedHours;
        
        public function __construct(database $database, $allowedHours)
        {
            $this->database = $database;
            $this->allowedHours = $allowedHours;
        }

        public function getTasks(){
            $taskQuery = "SELECT t.id, t.title, t.hours FROM dates AS d JOIN tasks AS t ON d.id = t.date_id WHERE date LIKE '" . $_GET['date'] . "'";
            $result = mysqli_query($this->database->getConnection(), $taskQuery);

            $response = [];
            if ($result->num_rows > 0) {
                while($row = mysqli_fetch_assoc($result)) {
                    $response[] = $row;
                }
            }

            echo json_encode($response);
            return false;
        }    

        public function addTask(){
            $connection = $this->database->getConnection();

            $dateQuery = "SELECT id FROM dates WHERE date LIKE '" . $_GET['date'] . "'";
            $result = mysqli_query($connection, $dateQuery);
            if ($result->num_rows > 0) {
                while($row = mysqli_fetch_assoc($result)) {
                    $dateID = $row['id'];
                }
                $hoursQuery = "SELECT SUM(hours) AS hours_sum FROM tasks WHERE date_id = " . $dateID;
                if (($this->getHoursSum($connection, $hoursQuery) + $_GET['hours']) < $this->allowedHours) {
                    $taskID = $this->insertTask($connection, $dateID);
                    echo json_encode($taskID);
                    return false;
                }
            } else {
                if($_GET['hours'] < $this->allowedHours) {
                    $insertQuery = "INSERT INTO dates VALUES (NULL, '".$_GET['date']."')";
                    mysqli_query($connection, $insertQuery);
                    $dateID = mysqli_insert_id($connection);
                    $taskID = $this->insertTask($connection, $dateID);
                    echo json_encode($taskID);
                    return false;
                }
            }

            echo json_encode(false);
            return false;
        }

        public function editTask(){
            $connection = $this->database->getConnection();
            $hoursQuery = "SELECT SUM(hours) AS hours_sum from tasks join dates ON tasks.date_id = dates.id WHERE dates.date LIKE '" . $_GET['date'] . "' AND tasks.id <> " . $_GET['id'];
            if (($this->getHoursSum($connection, $hoursQuery) + $_GET['hours']) < $this->allowedHours) {
                $updateQuery = "UPDATE tasks SET title = '" . $_GET['title'] . "', hours = '" . $_GET['hours'] . "' WHERE id = " . $_GET['id'];
                mysqli_query($connection, $updateQuery);
                echo json_encode(true);
                return false;
            }

            echo json_encode(false);
            return false;
        }

        public function deleteTask(){
            $deleteQuery = "DELETE FROM tasks WHERE id = " . $_GET['id'];
            mysqli_query($this->database->getConnection(), $deleteQuery);
            echo json_encode(true);
            return false;
        }

        public function getHoursSum($connection, $query) {
            $hours = mysqli_query($connection, $query);
            $hoursSum = mysqli_fetch_assoc($hours);
            return $hoursSum;
        }

        public function insertTask($connection, $dateID) {
            $insertQuery = "INSERT INTO tasks VALUES (NULL, ".$dateID.", '".$_GET['title']."', ".$_GET['hours'].", now(), NULL)";
            mysqli_query($connection, $insertQuery);
            $taskID = mysqli_insert_id($connection);
            return $taskID;
        }
    }
?>