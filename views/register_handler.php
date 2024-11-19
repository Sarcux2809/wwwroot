<?php
session_start(); // Iniciar sesión para usar variables de sesión

require_once('../config/database.php');

$database = new Database();
$conn = $database->getConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM usuarios WHERE email = :email OR nombre = :username";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $_SESSION['register_message'] = "Ya existe una cuenta con ese nombre de usuario o correo electrónico.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $role_id = 3;

        $sql = "INSERT INTO usuarios (nombre, email, contraseña, rol_id) VALUES (:username, :email, :password, :role_id)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':role_id', $role_id);

        if ($stmt->execute()) {
            $_SESSION['register_message'] = "Registro exitoso. Ahora puedes iniciar sesión.";
        } else {
            $_SESSION['register_message'] = "Error en el registro. Por favor, inténtalo de nuevo.";
        }
    }

    header("Location: login.php");
    exit();
}

$conn = null;
?>
