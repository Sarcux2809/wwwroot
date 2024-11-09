<?php
session_start(); // Asegúrate de iniciar la sesión
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'Editor') {
    header("Location: login.php"); // Si no está autenticado o no es editor, redirigir al login
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Editor</title>
</head>
<body>
    <h1>Bienvenido, Editor</h1>
    <p>Contenido exclusivo para Editores</p>
</body>
</html>
