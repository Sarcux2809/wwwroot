<?php
session_start();

// Si el usuario hace clic en el botón de cerrar sesión
if (isset($_POST['logout'])) {
    // Destruir la sesión
    session_unset();  // Eliminar todas las variables de sesión
    session_destroy();  // Destruir la sesión

    // Redirigir al login
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/../config/database.php';  // Incluir la clase Database

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirigir al login si no hay sesión
    exit();
}

// Crear una instancia de Database y obtener la conexión
$db = new Database();  // Instanciar la clase Database
$connection = $db->getConnection();  // Obtener la conexión

// Obtener el ID de usuario y su rol desde la sesión
$user_id = $_SESSION['user_id'];
$role = $_SESSION['user_role'];

// Asegúrate de que el modelo Document esté instanciado correctamente
require_once __DIR__ . '/../models/Document.php';
$documentModel = new Document($connection);  // Pasar $connection en lugar de $db

// Obtener los documentos según el rol
$documents = $documentModel->getDocuments($user_id, $role);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Usuario Regular</title>
    <link rel="stylesheet" href="../public/css/home_user.css">
</head>

<body>
    <div class="container">
        <header>
            <h1>Bienvenido, Usuario Regular</h1>
            <p>Contenido exclusivo para usuarios regulares</p>
        </header>

        <!-- Formulario para cerrar sesión -->
        <form action="" method="POST">
            <button type="submit" name="logout" class="logout-btn">Cerrar sesión</button>
        </form>

        <section class="document-list">
            <h2>Documentos Disponibles</h2>
            <p>A continuación, puedes ver los archivos a los cuales tienes acceso:</p>

            <!-- Lista de archivos disponibles -->
            <ul>
                <?php if (!empty($documents)): ?>
                    <?php foreach ($documents as $document): ?>
                        <li>
                            <!-- Enlace para descargar el documento usando download.php -->
                            <a href="download.php?id=<?php echo urlencode($document['id']); ?>">
                                Descargar <?php echo htmlspecialchars($document['nombre']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li>No hay documentos disponibles.</li>
                <?php endif; ?>
            </ul>
        </section>

        <footer>
            <p>&copy; 2024 Sistema de Gestión de Documentos</p>
        </footer>
    </div>
</body>

</html>
