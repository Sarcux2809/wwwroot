<?php
require_once __DIR__ . '/../models/Document.php';

class DocumentController
{
    private $model;
    private $user_id;
    private $user_role;

    public function __construct($model, $user_id, $user_role)
    {
        $this->model = $model;
        $this->user_id = $user_id;
        $this->user_role = $user_role;
    }

    // Mostrar los documentos según el rol
    
    public function showDocuments()
    {
        $documents = $this->model->getDocuments($this->user_id, $this->user_role);

        // Aquí pasas los documentos a la vista
        require_once __DIR__ . '/../views/home_admin.php';  // Incluye la vista
    }



    // Subir un nuevo documento
    public function uploadDocument($nombre, $ruta, $permiso_id)
    {
        return $this->model->uploadDocument($nombre, $ruta, $this->user_id, $permiso_id);
    }

    // Eliminar un documento
    public function deleteDocument($document_id)
    {
        return $this->model->deleteDocument($document_id);
    }
}
