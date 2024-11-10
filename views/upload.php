<?php
session_start();
require_once __DIR__ . '/../models/Document.php';
require_once __DIR__ . '/../config/database.php';

// Verificar si el usuario está autenticado y tiene el rol adecuado
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], ['Editor', 'Administrador'])) {
    header("Location: login.php"); // Redirige al login si no tiene los permisos adecuados
    exit();
    }

// Verificar que el archivo fue enviado y que no hay errores
if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
    // Obtener datos del archivo y el permiso seleccionado
    $fileTmpPath = $_FILES['file']['tmp_name'];
    $fileName = $_FILES['file']['name'];
    $permiso_nombre = $_POST['permiso'];

    // Depuración: Mostrar el nombre del permiso para verificarlo
    echo "Nombre del permiso recibido: " . $permiso_nombre . "<br>";

    // Instancia de la base de datos y el modelo Document
    $db = new Database();
    $documentModel = new Document($db->getConnection());

    // Obtener el ID del permiso
    $permiso_id = $documentModel->getPermisoId($permiso_nombre);  // Corregido a $documentModel
    if (!$permiso_id) {
        die("Error: permiso '" . $permiso_nombre . "' no válido o no encontrado.");
    }

    // Validar la extensión del archivo
    $allowedExtensions = ['pdf', 'txt', 'docx'];
    $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

    if (!in_array(strtolower($fileExtension), $allowedExtensions)) {
        echo "Error: Solo se permiten archivos con las extensiones: .pdf, .txt y .docx.";
        exit();
    }

    // Validar tamaño máximo de archivo (por ejemplo, 10 MB)
    $maxFileSize = 10 * 1024 * 1024; // 10 MB
    if ($_FILES['file']['size'] > $maxFileSize) {
        echo "Error: El archivo es demasiado grande. El tamaño máximo permitido es 10 MB.";
        exit();
    }

    // Limpiar el nombre del archivo para evitar caracteres problemáticos
    $fileNameClean = preg_replace('/[^a-zA-Z0-9_\.-]/', '_', $fileName); // Reemplaza caracteres no seguros por "_"

    // Definir la ruta donde se almacenarán los archivos
    $uploadDir = __DIR__ . '/../public/uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true); // Crear el directorio si no existe
    }
    $filePath = $uploadDir . basename($fileNameClean);

    // Mover el archivo a la carpeta de uploads
    if (move_uploaded_file($fileTmpPath, $filePath)) {
        // Subir el archivo usando el modelo Document
        $uploadSuccess = $documentModel->uploadDocument($fileNameClean, $filePath, $_SESSION['user_id'], $permiso_id);

        if ($uploadSuccess) {
            header("Location: home_admin.php?message=Archivo subido exitosamente");
            exit();
        } else {
            echo "Error al guardar el archivo en la base de datos.";
        }
    } else {
        echo "Error al mover el archivo a la carpeta de uploads.";
    }
} else {
    echo "Error: No se envió ningún archivo o hubo un error en la carga.";
}
?>
