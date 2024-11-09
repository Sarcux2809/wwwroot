<?php
session_start();

// Verificar si el usuario está autenticado y redirigir según el rol
if (isset($_SESSION['user_role'])) {
    $userRole = $_SESSION['user_role'];
    if ($userRole === 'Administrador') {
        header("Location: /views/home_admin.php");
        exit;
    } elseif ($userRole === 'Editor') {
        header("Location: /views/home_editor.php");
        exit;
    } elseif ($userRole === 'Usuario') {
        header("Location: /views/home_user.php");
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - Gestión de Documentos</title>
    <link rel="stylesheet" href="/public/css/styles.css">
</head>
<body>
    <header>
        <h1>Bienvenido a la Gestión de Documentos</h1>
    </header>
    <div class="container">
        <?php if (isset($_SESSION['user_role'])): ?>
            <p>Bienvenido, <?= htmlspecialchars($_SESSION['username']) ?>.</p>
            <p><a href="/logout.php">Cerrar sesión</a></p>
        <?php else: ?>
            <p><a href="/views/login.php">Iniciar sesión</a> para acceder al sistema.</p>
        <?php endif; ?>
    </div>
</body>
</html>
