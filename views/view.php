<?php
// Suponiendo que el archivo es de tipo texto o .txt
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
        // Mostrar el contenido del archivo (para texto o similar)
        $fileContent = file_get_contents($filePath);
        echo nl2br(htmlspecialchars($fileContent)); // Mostrar el contenido en el navegador
    } else {
        echo "El archivo no existe.";
    }
} else {
    echo "Archivo no encontrado.";
}
?>
