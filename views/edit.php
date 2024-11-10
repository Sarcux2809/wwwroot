<?php 
session_start();

// Verificar si el usuario está autenticado y tiene el rol adecuado
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], ['Editor', 'Administrador'])) {
header("Location: login.php"); // Redirige al login si no tiene los permisos adecuados
exit();
}

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

// Redirigir a la página de inicio (debería ser home_admin.php o home_editor.php dependiendo del rol)
if ($_SESSION['user_role'] == 'Administrador') {
header("Location: home_admin.php"); // Redirige a la página del administrador
} else {
header("Location: home_editor.php"); // Redirige a la página del editor
}
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