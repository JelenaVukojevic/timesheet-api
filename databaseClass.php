<?php
    class Database{

        protected $host;
        protected $database;
        protected $username;
        protected $password;

        public function __construct($host, $database, $username, $password){
            $this->host = $host;
            $this->database = $database;
            $this->username = $username;
            $this->password = $password;
        }

        public function getConnection() {
            $connection = new mysqli($this->host, $this->username, $this->password, $this->database);

            // Check connection
            if ($connection->connect_error) {
                die("Connection failed: " . $connection->connect_error);
            }

            return $connection;
        }
    }
?>