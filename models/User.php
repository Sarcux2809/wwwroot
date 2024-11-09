<?php
require_once __DIR__ . '/../config/database.php';

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

    // Método para obtener usuarios
    public function getUsers() {
        $query = "SELECT * FROM usuarios"; // Ajusta según tu esquema
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para obtener roles
    public function getRoles() {
        $query = "SELECT * FROM roles"; // Ajusta según tu esquema
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para asignar rol a un usuario
    public function asignarRol($usuario_id, $rol_id) {
        // Actualizar el rol del usuario en la base de datos
        $query = "UPDATE usuarios SET rol_id = :rol_id WHERE id = :usuario_id";

        // Preparar la consulta
        $stmt = $this->conn->prepare($query);

        // Vincular parámetros
        $stmt->bindParam(':rol_id', $rol_id);
        $stmt->bindParam(':usuario_id', $usuario_id);

        // Ejecutar la consulta y devolver el resultado
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
?>
