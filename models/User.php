<?php
require_once 'config/database.php';

class User {
    private $conn;
    private $table_name = "usuarios";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function login($email, $password) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE email = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $email);
        $stmt->execute();
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($password, $user['contraseña'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['rol'] = $user['rol_id'];
            return true;
        }
        return false;
    }

    public function register($nombre, $email, $password, $rol_id) {
        $query = "INSERT INTO " . $this->table_name . " (nombre, email, contraseña, rol_id) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $nombre);
        $stmt->bindParam(2, $email);
        $stmt->bindParam(3, password_hash($password, PASSWORD_BCRYPT));
        $stmt->bindParam(4, $rol_id);
        return $stmt->execute();
    }

    public function getRole($user_id) {
        $query = "SELECT roles.nombre FROM roles INNER JOIN usuarios ON roles.id = usuarios.rol_id WHERE usuarios.id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $user_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['nombre'];
    }
}
