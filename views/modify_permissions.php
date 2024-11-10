<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/User.php';

$db = new Database();
$userModel = new User($db->getConnection());

// Verificar si el usuario tiene permisos de administrador
// Verificar si el usuario tiene permisos de administrador
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Administrador') {
    header("Location: login.php");
    exit();
}


// Verificar si se ha enviado el formulario para modificar permisos
if (isset($_POST['modificar_permisos'])) {
    $usuario_id = $_POST['usuario_id'];
    $permissions = $_POST['permissions']; // Permisos seleccionados

    // Limpiar los permisos actuales y asignar los nuevos permisos
    if ($userModel->modificarPermisos($usuario_id, $permissions)) {
        $message = "Permisos modificados correctamente.";
    } else {
        $message = "Error al modificar los permisos.";
    }
}

// Redirigir a la página anterior (por ejemplo, home_admin.php)
header("Location: home_admin.php?message=" . urlencode($message));
exit();
?>
