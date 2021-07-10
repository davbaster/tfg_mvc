<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
</head>
<body>
    
    <?php $this->showMessages(); ?>

    <!-- sending form using POST -->
    <form class="p-2" action="<?php echo constant('URL');  ?>/signup/newUser" method="POST">
                        
                                                    
                            <div class="row mb-3 gx-3">
                                <div class="col">
                                    <input type="text" name="cedula" id="cedula" class="form-control form-control-lg" placeholder="Cedula" required>
                                    <div class="invalid-feedback">Cedula es requerido!</div>
                                </div>
                           </div>
                                                                       
                           <div class="row mb-3 gx-3">
                                <div class="col">
                                    <input type="text" name="nombre" id="nombre" class="form-control form-control-lg" placeholder="Nombre" required>
                                    <div class="invalid-feedback">Nombre es requerido!</div>
                                </div>
                            </div>
                        
                            <div class="row mb-3 gx-3">
                                <div class="col">
                                    <input type="text" name="apellido1" id="apellido1" class="form-control form-control-lg" placeholder="Apellido 1" required>
                                    <div class="invalid-feedback">Primer apellido es requerido!</div>
                                </div>
                            </div>

                            <div class="row mb-3 gx-3">
                                <div class="col">
                                    <input type="text" name="apellido2" id="apellido2" class="form-control form-control-lg" placeholder="Apellido 2" required>
                                    <div class="invalid-feedback">Segundo apellido es requerido!</div>
                                </div>
                            </div>

                        

                        <div class="mb-3">
                            <input type="tel" name="telefono" id="telefono" class="form-control form-control-lg" placeholder="Telefono">
                            <div class="invalid-feedback">Telefono es requerido</div>
                        </div>

                         <div class="mb-3">
                            <input type="text" name="direccion" id="direccion" class="form-control form-control-lg" placeholder="Direccion">
                            <div class="invalid-feedback">Ingrese una direccion</div>
                        </div>
                        
                        <div class="mb-3">
                            <input type="text" name="cuentaBancaria" id="cuentaBancaria" class="form-control form-control-lg" placeholder="Cuenta bancaria">
                            <div class="invalid-feedback">Cuenta es requerido</div>
                        </div>

                        <div class="mb-3">
                            <input type="email" name="email" id="email" class="form-control form-control-lg" placeholder="email">
                            <div class="invalid-feedback">Email es requerido</div>
                        </div>
                        
                        <div class="mb-3">
                            <input type="text" name="contrasena" id="contrasena" class="form-control form-control-lg" placeholder="Password">
                            <div class="invalid-feedback">Ingrese un password</div>
                        </div>
                        
                        <div class="mb-3">
                            <input type="text" name="confcontrasena" id="confcontrasena" class="form-control form-control-lg" placeholder="Confirme Password">
                            <div class="invalid-feedback">Confirmacion de password es requerido!</div>
                        </div>
                        <div id="passError" ></div>

                        <div class="mb-3">
                            <input type="rol" name="rol" id="rol" class="form-control form-control-lg" placeholder="rol del usuario" required>
                            <div class="invalid-feedback">Rol es requerido</div>
                        </div>


                        <div class="mb-3">
                            <input type="submit" value="Agregar Usuario" class="btn btn-primary btn-block btn-lg" id="add-user-btn">
                        </div>
                        <div><p>
                            Ya tienes una cuenta? <a href="<?php echo constant('URL'); ?>">Iniciar sesion</a>
                        </p></div>

                    </form>
    
</body>
</html>