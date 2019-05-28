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
    private $db_name = "excellenttaste";
    private $username = "root";
    private $password = "";
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