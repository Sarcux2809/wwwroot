<?php
// Incluir el archivo de configuración de la base de datos
require_once('../config/database.php');

// Crear una instancia de la clase Database
$database = new Database();

// Obtener la conexión
$conn = $database->getConnection();

// Verificar si la conexión fue exitosa
if ($conn) {
    echo "Conexión exitosa";
} else {
    echo "Error en la conexión";
}

// Recoger las variables del formulario de login
$username = $_POST['username']; // Suponiendo que el formulario tiene un campo llamado 'username'
$password = $_POST['password']; // Suponiendo que el formulario tiene un campo llamado 'password'

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
        // Guardar datos en la sesión
        session_start(); // Asegúrate de iniciar la sesión antes de guardar los datos
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
        echo "Contraseña incorrecta";
    }
} else {
    echo "Usuario no encontrado";
}

$conn = null; // Cerrar la conexión
?>
