<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'Administrador') {
    header("Location: login.php");
    exit();
}

// Asegurarse de que $documents esté definido
if (!isset($documents) || empty($documents)) {
    $documents = [];  // Inicializa $documents como un array vacío si no tiene datos
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Documentos - Administrador</title>
</head>
<body>
    <h1>Bienvenido Administrador</h1>
    <h2>Gestionar Archivos</h2>
    <form action="upload.php" method="POST" enctype="multipart/form-data">
        <label for="file">Subir Archivo:</label>
        <input type="file" id="file" name="file" required>
        <label for="permiso">Permiso:</label>
        <select name="permiso" id="permiso">
            <option value="Lectura">Lectura</option>
            <option value="Escritura">Escritura</option>
        </select>
        <button type="submit">Subir</button>
    </form>

    <h3>Archivos Subidos</h3>
    <ul>
        <?php foreach ($documents as $document): ?>
            <li>
                <?php echo htmlspecialchars($document['nombre']); ?>
                <a href="delete.php?id=<?php echo urlencode($document['id']); ?>">Eliminar</a>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
