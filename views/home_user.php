<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirigir al login si no hay sesión
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Usuario Regular</title>
</head>
<body>
    <h1>Bienvenido, Usuario Regular</h1>
    <p>Contenido exclusivo para usuarios regulares</p>
</body>
</html>