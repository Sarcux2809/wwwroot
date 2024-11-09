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
        // Forzar la descarga
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
        readfile($filePath);
        exit();
    } else {
        echo "El archivo no existe.";
    }
} else {
    echo "Archivo no encontrado.";
}
?>
