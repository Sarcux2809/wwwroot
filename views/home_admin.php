<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Document.php';

// Crear una instancia del modelo Database
$db = new Database();

// Crear una instancia del modelo User con la conexión de la base de datos
$userModel = new User($db->getConnection());

// Obtener los usuarios
$usuarios = $userModel->getUsers();

// Obtener los roles
$roleModel = new User($db->getConnection());
$roles = $roleModel->getRoles();

// Verificar si el usuario tiene permisos de administrador
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'Administrador') {
    header("Location: login.php");
    exit();
}

// Asegurarse de que 'user_name' esté definida en la sesión
$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Usuario no identificado';

// Crear una instancia del modelo Document
$documentModel = new Document($db->getConnection());

// Obtener los documentos subidos por el administrador
$documents = $documentModel->getDocuments($_SESSION['user_id'], $_SESSION['user_role']);

// Verificar si se ha enviado una solicitud para eliminar un documento
if (isset($_GET['delete_id'])) {
    $deleteId = $_GET['delete_id'];
    $documentModel->deleteDocument($deleteId);
    $message = "El archivo ha sido eliminado correctamente.";
}

// Cerrar sesión
if (isset($_POST['logout'])) {
    // Eliminar todas las variables de sesión
    session_unset();
    session_destroy();
    header("Location: login.php");  // Redirigir al inicio de sesión
    exit();
}

// Verificar si se ha enviado la solicitud para asignar rol
if (isset($_POST['asignar_rol'])) {
    $usuario_id = $_POST['usuario_id'];
    $rol_id = $_POST['rol_id'];

    // Llamar al método para asignar el rol
    if ($userModel->asignarRol($usuario_id, $rol_id)) {
        $message = "Rol asignado correctamente.";
    } else {
        $message = "Error al asignar rol.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Usuarios y Documentos - Administrador</title>
</head>
<body>
    <h1>Bienvenido , <?php echo htmlspecialchars($_SESSION['username'] ?? 'Usuario no identificado'); ?>!</h1>

    <!-- Formulario de Cerrar Sesión -->
    <form action="" method="POST">
        <button type="submit" name="logout">Cerrar Sesión</button>
    </form>

    <!-- Mensaje de confirmación -->
    <?php if (isset($message)): ?>
        <p style="color: green;"><?php echo $message; ?></p>
    <?php endif; ?>

    <h2>Gestionar Usuarios</h2>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Asignar Rol</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td><?php echo $usuario['id']; ?></td>
                    <td><?php echo htmlspecialchars($usuario['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                    <td><?php echo htmlspecialchars($usuario['rol'] ?? 'No asignado'); ?></td>
                    <td>
                        <form action="" method="POST">
                            <input type="hidden" name="usuario_id" value="<?php echo $usuario['id']; ?>">
                            <select name="rol_id">
                                <?php foreach ($roles as $rol): ?>
                                    <option value="<?php echo $rol['id']; ?>"><?php echo $rol['nombre']; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <button type="submit" name="asignar_rol">Asignar Rol</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h2>Gestionar Archivos</h2>

    <!-- Formulario para subir archivos -->
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

    <!-- Mostrar mensaje de eliminación -->
    <?php if (isset($message)): ?>
        <p style="color: green;"><?php echo $message; ?></p>
    <?php endif; ?>

    <h3>Archivos Subidos</h3>
    <ul>
        <?php if (!empty($documents)): ?>
            <?php foreach ($documents as $document): ?>
                <li>
                    <strong><?php echo htmlspecialchars($document['nombre']); ?></strong><br>
                    <a href="view.php?id=<?php echo urlencode($document['id']); ?>" target="_blank">Ver</a> |
                    <a href="download.php?id=<?php echo urlencode($document['id']); ?>">Descargar</a> |
                    <!-- Eliminar archivo con confirmación -->
                    <a href="?delete_id=<?php echo urlencode($document['id']); ?>" onclick="return confirm('¿Estás seguro de eliminar este archivo?')">Eliminar</a> |
                    <a href="edit.php?id=<?php echo urlencode($document['id']); ?>">Editar</a> <!-- Enlace para editar -->
                </li>
            <?php endforeach; ?>
        <?php else: ?>
            <li>No hay documentos subidos.</li>
        <?php endif; ?>
    </ul>
</body>
</html>
