<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Aplicacion Planillas</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/Login-Center.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body><div>

    <?php
        //Si usuario quiere devolverse, se redirigira a pagina principal 
        session_start();
        if(isset($_SESSION['user'])){
            header('location:principal.php');
        }

        // ini_set('display_errors', FALSE);
        ini_set ('log_errors', TRUE); //guarde errores en un archivo.
        ini_set("error_log", "/www/php-error.log");
        error_log('Inicio de la aplicacion');

        require_once 'libs/app.php';

        $app = new App();
    ?>
    
</div>
    <div class="container">
        <div class="row row-login" id="login-box">
            <div class="col-10 col-sm-6 col-md-4 offset-1 offset-sm-3 offset-md-4 my-auto">
                <h1>Jimenez y Cordoba</h1>
                <div>
                    <div class="card-body">
                        <h3>Acceso al sistema</h3>
                        <form id="login-form" class="px-3" action="#" method="post">
                            <div id="loginAlert"></div>
                            <div class="form-group mb-3"><label class="form-label">Usuario</label><input class="form-control rounded-0" type="text" id="cedula" name="cedula" placeholder="Cedula" required="" value="<?php if(isset( $_COOKIE['cedula'])) {echo $_COOKIE['cedula']; } ?>"></div>
                            <div class="form-group mb-3"><label class="form-label">Clave</label><input class="form-control rounded-0" type="password" id="password" name="password" placeholder="Clave" required="" value="<?php if(isset( $_COOKIE['password'])) {echo $_COOKIE['password']; } ?>"></div>
                            <div class="form-group mb-3">
                                <div class="form-check"><input class="form-check-input" type="checkbox" id="customCheck" name="rem" php="<?php if(isset($_COOKIE['cedula'])) { ?> checked <?php } ?>"><label class="form-check-label" for="formCheck-1">Recordarme</label></div>
                            </div><input id="login-btn" type="submit" value="login" class="btn btn-primary btn-lg d-block w-100 login-btn"><a class="btn btn-link center-block" role="button" href="#">Recobrar accesso</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/action.js"></script>
    <script src="assets/js/table.js"></script>
</body>

</html>