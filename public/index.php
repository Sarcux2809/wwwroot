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
    <link rel="stylesheet" href="/public/css/styles.css?v=<?php echo time(); ?>">
</head>
<body>
    <header>
        <h1>Gestión de Documentos</h1>
        <?php if (isset($_SESSION['user_role'])): ?>
            <p>Bienvenido, <?= htmlspecialchars($_SESSION['username']) ?>.</p>
        <?php endif; ?>
    </header>
    
    <div class="container">
        <?php if (isset($_SESSION['user_role'])): ?>
            <p><a href="/logout.php" class="logout-link">Cerrar sesión</a></p>
        <?php else: ?>
            <p><a href="/views/login.php" class="login-link">Iniciar sesión</a> para acceder al sistema.</p>
        <?php endif; ?>
    </div>
    
    <footer>
        <p>&copy; 2024 Sistema de Gestión de Archivos</p>
    </footer>
</body>
</html>
