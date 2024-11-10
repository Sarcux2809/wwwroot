<?php
require_once __DIR__ . '/../config/database.php';

class User
{
    private $conn;
    private $table_name = "usuarios";

    public function __construct($conn = null)
    {
        $this->conn = $conn ?? (new Database())->getConnection();
    }

    // Método para login de usuario
    public function login($email, $password)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE email = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $email);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($password, $user['contraseña'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['nombre'];
            $_SESSION['rol_id'] = $user['rol_id'];
            return true;
        }
        return false;
    }

    // Obtener los usuarios
    public function getUsers()
    {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener roles
    public function getRoles()
    {
        $query = "SELECT * FROM roles";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener permisos
    public function getPermissions()
    {
        $query = "SELECT * FROM permisos";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Asignar rol a un usuario
    public function asignarRol($usuario_id, $rol_id)
    {
        $query = "UPDATE " . $this->table_name . " SET rol_id = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $rol_id);
        $stmt->bindParam(2, $usuario_id);
        return $stmt->execute();
    }

    // Obtener permisos de un usuario
    // Obtener permisos de un usuario basado en su rol
public function getUserPermissions($usuario_id)
{
    $query = "SELECT p.nombre 
              FROM permisos p
              JOIN role_permissions rp ON rp.permission_id = p.id
              JOIN roles r ON rp.role_id = r.id
              JOIN usuarios u ON u.rol_id = r.id
              WHERE u.id = ?";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(1, $usuario_id);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

}
?>
