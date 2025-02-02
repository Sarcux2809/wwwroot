<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Document.php';

// Crear una instancia del modelo Database
$db = new Database();
$userModel = new User($db->getConnection());

// Verificar si el usuario tiene permisos de administrador
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Administrador') {
    header("Location: login.php");
    exit();
}

// Acceder al rol de usuario correctamente
echo "El rol del usuario es: " . $_SESSION['user_role'];

// Asegurarse de que 'user_name' esté definida en la sesión
$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Usuario no identificado';

// Obtener los usuarios
$usuarios = $userModel->getUsers();

// Obtener los roles
$roles = $userModel->getRoles();

// Crear una instancia del modelo Document
$documentModel = new Document($db->getConnection());

// Obtener los documentos subidos por el administrador
$documents = $documentModel->getDocuments($_SESSION['user_id'], $_SESSION['user_role']);

// Definir mensajes específicos
$delete_message = null;
$role_message = null;

// Verificar si se ha enviado una solicitud para eliminar un documento
if (isset($_GET['delete_id'])) {
    $deleteId = $_GET['delete_id'];
    $documentModel->deleteDocument($deleteId);
    $delete_message = "El archivo ha sido eliminado correctamente.";
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
        $role_message = "Rol asignado correctamente.";
    } else {
        $role_message = "Error al asignar rol.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios y Documentos - Administrador</title>
    <link rel="stylesheet" href="/public/css/home_admin.css?v=<?php echo time(); ?>">
</head>

<body>
    <header>
        <h1>Bienvenido, <?php echo htmlspecialchars($_SESSION['username'] ?? 'Usuario no identificado'); ?>!</h1>
        <form action="" method="POST">
            <button type="submit" name="logout">Cerrar Sesión</button>
        </form>
    </header>

    <!-- Mostrar mensajes -->
    <?php if (isset($delete_message)): ?>
        <div class="message success"><?php echo $delete_message; ?></div>
    <?php endif; ?>
    <?php if (isset($role_message)): ?>
        <div class="message success"><?php echo $role_message; ?></div>
    <?php endif; ?>

    <main>
        <section class="user-management">
            <h2>Gestionar Usuarios</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Permisos</th>
                        <th>Rol</th>
                        <th>Asignar Rol</th>
                        <th>Modificar Permisos</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $usuario): ?>
                        <tr>
                            <td><?php echo $usuario['id']; ?></td>
                            <td><?php echo htmlspecialchars($usuario['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                            <td>
                                <?php
                                $permisos = $userModel->getUserPermissions($usuario['id']);
                                if (!empty($permisos)) {
                                    foreach ($permisos as $permiso) {
                                        echo htmlspecialchars($permiso['nombre']) . "<br>";
                                    }
                                } else {
                                    echo "No hay permisos asignados.";
                                }
                                ?>
                            </td>
                            <td><?php echo htmlspecialchars($usuario['rol_id'] ?? 'No asignado'); ?></td>
                            <td>
                                <form action="" method="POST">
                                    <input type="hidden" name="usuario_id" value="<?php echo $usuario['id']; ?>">
                                    <select name="rol_id">
                                        <?php foreach ($roles as $rol): ?>
                                            <option value="<?php echo $rol['id']; ?>" <?php echo ($rol['id'] == $usuario['rol_id']) ? 'selected' : ''; ?>>
                                                <?php echo $rol['nombre']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button type="submit" name="asignar_rol">Asignar Rol</button>
                                </form>
                            </td>
                            <!-- Parte de la tabla donde se gestionan los permisos -->
                            <td>
                                <form action="modify_permissions.php" method="POST">
                                    <input type="hidden" name="usuario_id" value="<?php echo $usuario['id']; ?>">
                                    <select name="permissions[]" multiple>
                                        <?php
                                        $permissions = $userModel->getPermissions(); // Obtener todos los permisos disponibles
                                        foreach ($permissions as $permiso):
                                        ?>
                                            <option value="<?php echo $permiso['id']; ?>"
                                                <?php echo in_array($permiso['id'], array_column($permisos, 'id')) ? 'selected' : ''; ?>>
                                                <?php echo $permiso['nombre']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button type="submit" name="modificar_permisos">Modificar Permisos</button>
                                </form>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>

        <section class="document-management">
            <h2>Gestionar Archivos</h2>
            <form action="upload.php" method="POST" enctype="multipart/form-data">
                <input type="file" id="file" name="file" required>
                <select name="permiso" id="permiso">
                    <option value="Lectura">Lectura</option>
                    <option value="Escritura">Escritura</option>
                </select>
                <button type="submit">Subir Archivo</button>
            </form>

            <h3>Archivos Subidos</h3>
            <ul>
                <?php if (!empty($documents)): ?>
                    <?php foreach ($documents as $document): ?>
                        <li>
                            <strong><?php echo htmlspecialchars($document['nombre']); ?></strong><br>
                            <a href="view.php?id=<?php echo urlencode($document['id']); ?>" target="_blank">Ver</a> |
                            <a href="download.php?id=<?php echo urlencode($document['id']); ?>">Descargar</a> |
                            <a href="?delete_id=<?php echo urlencode($document['id']); ?>" onclick="return confirm('¿Estás seguro de eliminar este archivo?')">Eliminar</a> |
                            <a href="edit.php?id=<?php echo urlencode($document['id']); ?>">Editar</a>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li>No hay documentos subidos.</li>
                <?php endif; ?>
            </ul>
        </section>
    </main>
</body>

</html>