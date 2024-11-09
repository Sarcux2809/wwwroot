<?php
session_start();
require_once '../controllers/DocumentController.php';

$action = $_GET['action'] ?? '';

if ($action == 'upload') {
    $controller = new DocumentController();
    $controller->upload($_POST['nombre'], $_FILES['file'], $_SESSION['user_id'], $_POST['permiso_id']);
}
?>
