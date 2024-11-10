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
$documentModel = new Document($db->getConnection());

// Obtener los documentos que el editor puede ver
$documents = $documentModel->getDocuments($_SESSION['user_id'], $_SESSION['user_role']);

// Cerrar sesión si se hace clic en el enlace
if (isset($_GET['logout'])) {
    session_destroy(); // Destruir la sesión
    header("Location: login.php"); // Redirigir al login
    exit();
}

// Verificar si se ha subido un archivo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];

    // Validar el archivo (tipo, tamaño, etc.)
    if ($file['error'] === UPLOAD_ERR_OK) {
        // Aquí puedes realizar el procesamiento y almacenamiento del archivo
        // Por ejemplo, moverlo a una carpeta específica y guardar la referencia en la base de datos
        $uploadDir = __DIR__ . '/../public/uploads/';
        $uploadFile = $uploadDir . basename($file['name']);
        
        if (move_uploaded_file($file['tmp_name'], $uploadFile)) {
            // Obtener el permiso asociado (debes adaptarlo según tu lógica)
            // Ejemplo: Permiso por defecto o basado en alguna lógica (ajustar según sea necesario)
            $permisoId = $documentModel->getPermisoId('Escritura'); // Ejemplo: obtener ID de permiso de "Escritura"

            // Guardar el documento en la base de datos
            $documentModel->uploadDocument($file['name'], $uploadFile, $_SESSION['user_id'], $permisoId);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Editor</title>
    <link rel="stylesheet" href="/public/css/home_editor.css?v=<?php echo time(); ?>">
</head>

<body>
    <header>
        <div class="container">
            <h1>Bienvenido, Editor</h1>
            <nav>
                <ul>
                    <li><a href="?logout=true">Cerrar Sesión</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <div class="container">
            <section>
                <p>Contenido exclusivo para Editores</p>
                <p>Aquí puedes ver, descargar o editar los archivos que te pertenecen.</p>
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
                                <span>(<?php echo htmlspecialchars($document['permiso']); ?>)</span>
                                
                                <!-- Opción de Descargar -->
                                <a href="download.php?id=<?php echo urlencode($document['id']); ?>">Descargar</a>

                                <!-- Opción de Editar, solo si tiene permiso de lectura o escritura -->
                                <?php if ($document['permiso'] == 'Lectura' || $document['permiso'] == 'Escritura'): ?>
                                    <a href="edit.php?id=<?php echo urlencode($document['id']); ?>">Editar</a>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li>No hay documentos disponibles.</li>
                    <?php endif; ?>
                </ul>
            </section>

            <!-- Formulario para subir archivos -->
            <section>
                <h2>Subir Archivo</h2>
                <form action="" method="POST" enctype="multipart/form-data">
                    <label for="file">Seleccionar archivo:</label>
                    <input type="file" id="file" name="file" required>
                    <button type="submit">Subir Archivo</button>
                </form>
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
