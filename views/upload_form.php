<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Subir Documento</title>
    <link rel="stylesheet" href="/public/css/styles.css">
</head>
<body>
    <header>
        <h1>Subir Documento</h1>
    </header>
    <div class="container">
        <form action="/upload" method="POST" enctype="multipart/form-data">
            <label for="file">Selecciona un archivo:</label>
            <input type="file" id="file" name="file" required>

            <label for="access">Permisos de acceso:</label>
            <select id="access" name="access">
                <option value="public">Público</option>
                <option value="restricted">Restringido</option>
            </select>

            <button type="submit">Subir Documento</button>
        </form>
    </div>
</body>
</html>



<form action="index.php?action=upload" method="post" enctype="multipart/form-data">
    Nombre del archivo: <input type="text" name="nombre"><br>
    Seleccionar archivo: <input type="file" name="file"><br>
    Permiso: 
    <select name="permiso_id">
        <option value="1">Lectura</option>
        <option value="2">Edición</option>
    </select><br>
    <input type="submit" value="Subir archivo">
</form>
