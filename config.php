<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "timesheet";
$api_key = 'f4f458161b007ebd8a00df75d428b8bd';
$allowedHours = 8;

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

header("Access-Control-Allow-Origin: *");
?>
