<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <link rel="stylesheet" href="<?php echo constant('URL'); ?>/public/css/login.css">
</head>
<body>
    <?php require 'views/header.php'; ?>

    <div id="login-main">
        <form action="<?php echo constant('URL'); ?>/login/authenticate" method="POST">
        <div></div>
            <h2>Iniciar sesión</h2>

            <p>
                <label for="cedula">Cedula</label>
                <input type="text" name="cedula" id="cedula" autocomplete="off">
            </p>
            <p>
                <label for="password">Clave</label>
                <input type="password" name="contrasena" id="contrasena" autocomplete="off">
            </p>
            <p>
                <input type="submit" value="Iniciar sesión" />
            </p>

            <p>
                <!-- ¿No tienes cuenta? <a href="<?php //echo constant('URL'); ?>/signup">Registrarse</a> -->
            </p>
        </form>
    </div>
</body>
</html>