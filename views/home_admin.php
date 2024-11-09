<?php
session_start(); // Asegúrate de iniciar la sesión
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'Administrador') {
    header("Location: login.php"); // Si no está autenticado o no es administrador, redirigir al login
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Administrador</title>
</head>
<body>
    <h1>Bienvenido, Administrador</h1>
    <p>Contenido exclusivo para Administradores</p>
</body>
</html>
