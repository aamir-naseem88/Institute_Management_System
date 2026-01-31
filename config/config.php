<?php
// ==========connection with pdo=========
class Database{
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $db_name = "academy_system02";

    public $conn;
    public function __construct(){
        try{
            //connect to database
            $this->conn = new PDO ("mysql:dbname={$this->db_name};host={$this->host};",$this->username, $this->password);
            //set default attributes
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            return $this->conn;
        }catch (PDOException $err) {
        die("Database connection failed: " . $err->getMessage());
    }
        }
}

?>