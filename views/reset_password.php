<?php
session_start(); // Iniciar sesión

// Incluir archivo de conexión a la base de datos
require_once('../config/database.php');

// Verifica si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recibir los datos del formulario
    $email = $_POST['email'];
    $new_password = $_POST['new_password'];

    // Crear una instancia de la clase Database
    $database = new Database();
    $db = $database->getConnection();

    // Verificar si el correo existe en la base de datos
    $query = "SELECT id FROM usuarios WHERE email = :email";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // El correo existe, actualizamos la contraseña
        $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT); // Encriptar la nueva contraseña

        // Actualizar la contraseña en la base de datos
        $update_query = "UPDATE usuarios SET contraseña = :new_password WHERE email = :email";
        $update_stmt = $db->prepare($update_query);
        $update_stmt->bindParam(':new_password', $new_password_hash);
        $update_stmt->bindParam(':email', $email);

        if ($update_stmt->execute()) {
            // Si la contraseña se actualizó correctamente, redirigimos al usuario a login
            $_SESSION['message'] = "Contraseña actualizada con éxito. Puedes iniciar sesión ahora.";
            header("Location: login.php"); // Redirigir al formulario de login
            exit();
        } else {
            $_SESSION['error'] = "Hubo un error al actualizar la contraseña. Inténtalo de nuevo.";
        }
    } else {
        // Si el correo no existe en la base de datos
        $_SESSION['error'] = "El correo electrónico no está registrado.";
    }
}
?>
