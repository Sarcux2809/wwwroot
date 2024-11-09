<?php
require_once __DIR__ . '/../config/database.php';

class Document {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Obtener todos los documentos con permisos
    public function getDocuments($user_id, $role) {
        if ($role == 'Administrador') {
            $sql = "SELECT * FROM documentos";
        } elseif ($role == 'Editor') {
            $sql = "SELECT * FROM documentos WHERE subido_por = :user_id";
        } else { // Usuario Regular
            $sql = "SELECT * FROM documentos WHERE permiso_id = (SELECT id FROM permisos WHERE nombre = 'Lectura')";
        }

        $stmt = $this->conn->prepare($sql);
        if ($role == 'Editor') {
            $stmt->bindValue(':user_id', $user_id);
        }
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener el ID del permiso basado en el nombre
    public function getPermisoId($nombre_permiso) {
        $sql = "SELECT id FROM permisos WHERE nombre = :nombre_permiso";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':nombre_permiso', $nombre_permiso);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['id'] : null;
    }

    // Subir un archivo
    public function uploadDocument($nombre, $ruta, $subido_por, $permiso_id) {
        $sql = "INSERT INTO documentos (nombre, ruta, subido_por, permiso_id) VALUES (:nombre, :ruta, :subido_por, :permiso_id)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':nombre', $nombre);
        $stmt->bindValue(':ruta', $ruta);
        $stmt->bindValue(':subido_por', $subido_por);
        $stmt->bindValue(':permiso_id', $permiso_id);
        return $stmt->execute();
    }

    // Eliminar un archivo
    public function deleteDocument($document_id) {
        $sql = "DELETE FROM documentos WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $document_id);
        return $stmt->execute();
    }

    // Obtener un documento específico por su ID
    public function getDocumentById($document_id) {
        $sql = "SELECT * FROM documentos WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $document_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
