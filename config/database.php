<?php
class Database {
    private static $host = "localhost";
    private static $db_name = "document_manager";
    private static $username = "root";
    private static $password = "2809";
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . self::$host . ";dbname=" . self::$db_name, self::$username, self::$password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
            die();
        }        
        return $this->conn;
    }
}
