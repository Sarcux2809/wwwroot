<?php
session_start(); // Iniciar sesión para manejar variables de sesión
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="/public/css/styles.css?v=<?php echo time(); ?>">
    <script>
        function toggleSection(sectionId) {
            const sections = document.querySelectorAll('.toggle-section');
            sections.forEach(section => section.style.display = 'none');
            document.getElementById(sectionId).style.display = 'block';
        }
    </script>
</head>

<body>
    <header>
        <h1>Gestión de Documentos - Login</h1>
    </header>

    <div class="container">
        <?php
        if (isset($_SESSION['message'])) {
            echo "<p class='success'>" . $_SESSION['message'] . "</p>";
            unset($_SESSION['message']);
        }
        if (isset($_SESSION['error'])) {
            echo "<p class='error'>" . $_SESSION['error'] . "</p>";
            unset($_SESSION['error']);
        }
        ?>

        <form action="login_handler.php" method="POST">
            <label for="username">Usuario:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Ingresar</button>
        </form>

        <p>
            <a href="javascript:void(0)" onclick="toggleSection('reset')">¿Olvidaste tu contraseña?</a>
        </p>
        <p>
            <a href="javascript:void(0)" onclick="toggleSection('register')">¿No tienes cuenta? Regístrate aquí</a>
        </p>

        <div id="reset" class="toggle-section" style="display:none;">
            <h2>Restablecer Contraseña</h2>
            <form action="reset_password.php" method="POST">
                <label for="reset_email">Correo electrónico:</label>
                <input type="email" id="reset_email" name="email" required>

                <label for="reset_new_password">Nueva contraseña:</label>
                <input type="password" id="reset_new_password" name="new_password" required>

                <button type="submit">Restablecer</button>
            </form>
        </div>

        <div id="register" class="toggle-section" style="display:none;">
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
</body>

</html>
