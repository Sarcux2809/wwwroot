<?
session_start(); // Agregar al inicio de la clase o método
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="/public/css/styles.css">
</head>
<body>
    <header>
        <h1>Gestión de Documentos - Login</h1>
    </header>

    <div class="container">
        <!-- Formulario de login -->
        <form action="login_handler.php" method="POST">
            <label for="username">Usuario:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Ingresar</button>
        </form>

        <p>¿No tienes cuenta? <a href="#register">Regístrate aquí</a></p>

        <!-- Formulario de registro (oculto al inicio) -->
        <div id="register" style="display:none;">
            <h2>Registro de Usuario</h2>
            <form action="register_handler.php" method="POST">
                <label for="reg_username">Usuario:</label>
                <input type="text" id="reg_username" name="username" required>

                <label for="reg_email">Correo electrónico:</label>
                <input type="email" id="reg_email" name="email" required>

                <label for="reg_password">Contraseña:</label>
                <input type="password" id="reg_password" name="password" required>

                <button type="submit">Registrarse</button>
            </form>
        </div>
    </div>

    <script>
        // Mostrar el formulario de registro cuando el usuario hace clic en "Regístrate aquí"
        document.querySelector("a[href='#register']").addEventListener("click", function() {
            document.getElementById("register").style.display = "block";
        });
    </script>
</body>
</html>
