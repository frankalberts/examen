<?php
/**
 * Created by PhpStorm.
 * User: Frank
 * Date: 21-1-2019
 * Time: 09:28
 */

class Database
{
    private $host = "localhost";
    private $db_name = "fraalb_excellenttaste";
    private $username = "fraalb_12";
    private $password = "welkom";
    public $conn;

    public function getConnection(){

        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
        }catch (PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->conn;
    }
}