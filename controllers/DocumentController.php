<?php
require_once __DIR__. '/../models/Document.php';

class DocumentController {
    public function upload($nombre, $file, $user_id, $permiso_id) {
        $document = new Document();
        $target_dir = "public/uploads/";
        $target_file = $target_dir . basename($file["name"]);
        
        if (move_uploaded_file($file["tmp_name"], $target_file)) {
            $document->uploadDocument($nombre, $target_file, $user_id, $permiso_id);
            return true;
        }
        return false;
    }

    public function listDocuments($user_id) {
        $document = new Document();
        $user = new User();
        $role = $user->getRole($user_id);
        return $document->getDocumentsByPermission($user_id, $role);
    }
}
