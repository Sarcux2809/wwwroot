<?php
// Incluir el archivo de configuración de la base de datos
require_once('../config/database.php');

// Crear una instancia de la clase Database
$database = new Database();
$conn = $database->getConnection();

session_start(); // Iniciar sesión para manejar variables de sesión

if ($conn) {
    // Recoger las variables del formulario
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Consultar usuario por nombre
    $sql = "SELECT usuarios.id, usuarios.nombre, usuarios.contraseña, roles.nombre AS rol
            FROM usuarios
            JOIN roles ON usuarios.rol_id = roles.id
            WHERE usuarios.nombre = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(1, $username, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        // Verificar la contraseña
        if (password_verify($password, $result['contraseña'])) {
            $_SESSION['user_id'] = $result['id'];
            $_SESSION['username'] = $result['nombre'];
            $_SESSION['user_role'] = $result['rol'];

            // Redirigir según el rol
            switch ($result['rol']) {
                case 'Administrador':
                    header("Location: ../views/home_admin.php");
                    break;
                case 'Editor':
                    header("Location: ../views/home_editor.php");
                    break;
                case 'Usuario Regular':
                    header("Location: ../views/home_user.php");
                    break;
            }
            exit;
        } else {
            $_SESSION['error'] = "Contraseña incorrecta.";
        }
    } else {
        $_SESSION['error'] = "Usuario no encontrado.";
    }
} else {
    $_SESSION['error'] = "Error en la conexión a la base de datos.";
}

// Redirigir de vuelta al formulario de login
header("Location: ../views/login.php");
exit;
?>
