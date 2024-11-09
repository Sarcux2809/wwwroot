<?php
// Incluir el archivo de configuración de la base de datos
require_once('../config/database.php');

// Crear una instancia de la clase Database
$database = new Database();
$conn = $database->getConnection();

// Verificar si se ha enviado el formulario de registro
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validar que el usuario no exista ya
    $sql = "SELECT * FROM usuarios WHERE email = :email OR nombre = :username";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // Si ya existe un usuario con ese email o nombre de usuario, mostrar un error
        echo "Ya existe una cuenta con ese nombre de usuario o correo electrónico.";
    } else {
        // Si no existe, proceder con el registro
        $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hashear la contraseña

        // Asignar el rol "Usuario" (id=3 según lo insertado previamente en la tabla roles)
        $role_id = 3; // Rol "Usuario"

        // Insertar el nuevo usuario en la base de datos
        $sql = "INSERT INTO usuarios (nombre, email, contraseña, rol_id) VALUES (:username, :email, :password, :role_id)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':role_id', $role_id);

        if ($stmt->execute()) {
            // Registro exitoso, redirigir al login
            header("Location: login.php"); // Redirige a la página de login
            exit(); // Detiene la ejecución del script después de la redirección
        } else {
            echo "Error en el registro. Por favor, inténtalo de nuevo.";
        }
    }
}

$conn = null; // Cerrar la conexión
?>
