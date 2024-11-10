<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Document.php';

// Verificar si el usuario tiene permisos de editor
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'Editor') {
    header("Location: login.php");
    exit();
}

// Crear una instancia del modelo Database
$db = new Database();

// Crear una instancia del modelo Document con la conexión de la base de datos
$documentModel = new Document($db->getConnection());

// Obtener los documentos que el editor puede ver
$documents = $documentModel->getDocuments($_SESSION['user_id'], $_SESSION['user_role']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Editor</title>
    <link rel="stylesheet" href="../public/css/home_editor.css"> <!-- Enlace al archivo CSS -->
</head>
<body>
    <header>
        <div class="container">
            <h1>Bienvenido, Editor</h1>
            <nav>
                <ul>
                    <li><a href="upload.php">Subir Archivos</a></li>
                    <li><a href="manage_files.php">Gestionar Archivos</a></li>
                    <li><a href="logout.php">Cerrar Sesión</a></li>
                </ul>
            </nav>
        </div>
    </header>
    
    <main>
        <div class="container">
            <section>
                <p>Contenido exclusivo para Editores</p>
                <p>Aquí puedes cargar archivos nuevos o modificar los archivos que te pertenecen.</p>
            </section>
            
            <section>
                <h2>Archivos Cargados</h2>
                <ul>
                    <?php if (!empty($documents)): ?>
                        <?php foreach ($documents as $document): ?>
                            <li>
                                <a href="view.php?id=<?php echo urlencode($document['id']); ?>" target="_blank">
                                    <?php echo htmlspecialchars($document['nombre']); ?>
                                </a>
                                <!-- Aquí puedes agregar más opciones según los permisos -->
                                <span>(<?php echo htmlspecialchars($document['permiso']); ?>)</span>
                                <?php if ($document['permiso'] == 'Escritura'): ?>
                                    <a href="edit.php?id=<?php echo urlencode($document['id']); ?>">Editar</a>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li>No hay documentos disponibles.</li>
                    <?php endif; ?>
                </ul>
            </section>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2024 Sistema de Gestión de Archivos</p>
        </div>
    </footer>
</body>
</html>
