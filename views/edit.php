<?php
session_start();
require_once __DIR__ . '/../models/Document.php';
require_once __DIR__ . '/../config/database.php';

if (!isset($_GET['id'])) {
    echo "No se especificó el archivo.";
    exit();
}

$documentId = $_GET['id'];

// Crear instancia de la base de datos y el modelo
$db = new Database();
$documentModel = new Document($db->getConnection());

// Obtener el archivo desde la base de datos
$document = $documentModel->getDocumentById($documentId);

if ($document) {
    $filePath = $document['ruta']; // Ruta al archivo
    if (file_exists($filePath)) {
        $fileContent = file_get_contents($filePath);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Guardar cambios
            $newContent = $_POST['content'];
            file_put_contents($filePath, $newContent);
            echo "Archivo actualizado con éxito.";
            
            // Redirigir a la página de inicio del administrador
            header("Location: home_admin.php");  // Aquí debe ir la ruta correcta a tu vista de administración
            exit();
        } else {
            // Formulario para editar el archivo
            echo '<form method="POST">';
            echo '<textarea name="content" rows="10" cols="50">' . htmlspecialchars($fileContent) . '</textarea><br>';
            echo '<button type="submit">Guardar Cambios</button>';
            echo '</form>';
        }
    } else {
        echo "El archivo no existe.";
    }
} else {
    echo "Archivo no encontrado.";
}
?>
