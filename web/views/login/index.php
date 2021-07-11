<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Aplicacion Planillas</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <!-- <link rel="stylesheet" href="assets/css/Login-Center.css"> -->
    <!-- <link rel="stylesheet" href="assets/css/styles.css"> -->
</head>

<body><div>

</div>
    <div class="container">
        <div class="row row-login" id="login-box">
            <div class="col-10 col-sm-6 col-md-4 offset-1 offset-sm-3 offset-md-4 my-auto">
                <p><?php $this->showMessages(); ?></p>
                <h1>Jimenez y Cordoba</h1>
                <div>
                    <div class="card-body">
                        <h3>Acceso al sistema</h3>
                        <form id="login-main" class="px-3" action="<?php echo constant('URL'); ?>/login/authenticate" method="POST">
                            <div><?php ( isset($this->errorMessage)) ? $this->errorMessage : '' ?></div>
                            <div class="form-group mb-3"><label class="form-label">Cedula</label><input class="form-control rounded-0" type="text" id="cedula" name="cedula" placeholder="Cedula" required="" autocomplete="off"></div>
                            <div class="form-group mb-3"><label class="form-label">Clave</label><input class="form-control rounded-0" type="password" id="contrasena" name="contrasena" placeholder="Clave" required="" autocomplete="off"></div>
                            <div class="form-group mb-3">
                            </div>
                            <input id="login-btn" type="submit" value="login" class="btn btn-primary btn-lg d-block w-100 login-btn">
                            <a class="btn btn-link center-block" role="button" href="<?php echo constant('URL'); ?>/signup">Registrarse</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>

</body>

</html>