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

    // Obtener permisos de un usuario basado en su rol
    public function getUserPermissions($usuario_id)
    {
        $query = "SELECT p.id, p.nombre 
              FROM permisos p
              JOIN user_permissions up ON up.permission_id = p.id
              WHERE up.user_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $usuario_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // Modificar permisos de un usuario (con opción de agregar todos los permisos)
public function modificarPermisos($usuario_id, $permisos_ids, $asignar_todos = false)
{
    // Si se debe asignar todos los permisos, obtén todos los permisos disponibles
    if ($asignar_todos) {
        $query = "SELECT id FROM permisos";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $permisos_ids = $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    // Primero, eliminamos los permisos existentes del usuario
    $query = "DELETE FROM user_permissions WHERE user_id = ?";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(1, $usuario_id);
    $stmt->execute();

    // Ahora, asignamos los nuevos permisos seleccionados
    foreach ($permisos_ids as $permiso_id) {
        $query = "INSERT INTO user_permissions (user_id, permission_id) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $usuario_id);
        $stmt->bindParam(2, $permiso_id);
        $stmt->execute();
    }

    return true;
}

}