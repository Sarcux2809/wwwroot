<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Documentos Disponibles</title>
    <link rel="stylesheet" href="/public/css/styles.css">
</head>
<body>
    <header>
        <h1>Documentos Disponibles</h1>
    </header>
    <div class="container">
        <table>
            <tr>
                <th>Nombre</th>
                <th>Fecha de Subida</th>
                <th>Acceso</th>
                <th>Acciones</th>
            </tr>
            <?php foreach ($documents as $document): ?>
                <tr>
                    <td><?= htmlspecialchars($document['name']) ?></td>
                    <td><?= htmlspecialchars($document['upload_date']) ?></td>
                    <td><?= htmlspecialchars($document['access']) ?></td>
                    <td>
                        <a href="/download?id=<?= $document['id'] ?>">Descargar</a>
                        <?php if ($user_role === 'Administrador' || ($user_role === 'Editor' && $document['owner_id'] === $user_id)): ?>
                            | <a href="/delete?id=<?= $document['id'] ?>">Eliminar</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>
