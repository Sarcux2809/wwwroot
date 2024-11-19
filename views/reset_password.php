<?php
session_start();
require_once('../config/database.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $new_password = $_POST['new_password'];

    $database = new Database();
    $db = $database->getConnection();

    $query = "SELECT id FROM usuarios WHERE email = :email";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);

        $update_query = "UPDATE usuarios SET contraseña = :new_password WHERE email = :email";
        $update_stmt = $db->prepare($update_query);
        $update_stmt->bindParam(':new_password', $new_password_hash);
        $update_stmt->bindParam(':email', $email);

        if ($update_stmt->execute()) {
            $_SESSION['message'] = "Contraseña actualizada con éxito. Puedes iniciar sesión ahora.";
        } else {
            $_SESSION['error'] = "Hubo un error al actualizar la contraseña. Inténtalo de nuevo.";
        }
    } else {
        $_SESSION['error'] = "El correo electrónico no está registrado.";
    }

    header("Location: login.php");
    exit();
}
?>
