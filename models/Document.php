<?php
require_once __DIR__ . '/../config/database.php';

class Document {
    private $conn;
    private $table_name = "documentos";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function uploadDocument($nombre, $ruta, $subido_por, $permiso_id) {
        $query = "INSERT INTO " . $this->table_name . " (nombre, ruta, subido_por, permiso_id) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $nombre);
        $stmt->bindParam(2, $ruta);
        $stmt->bindParam(3, $subido_por);
        $stmt->bindParam(4, $permiso_id);
        return $stmt->execute();
    }

    public function getDocumentsByPermission($user_id, $role) {
        if ($role === 'Administrador') {
            $query = "SELECT * FROM " . $this->table_name;
        } else {
            $query = "SELECT * FROM " . $this->table_name . " WHERE permiso_id = 1"; // Solo archivos de lectura
        }
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
