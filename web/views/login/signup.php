<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="<?php echo constant('URL'); ?>public/css/login.css">
    <title>Signup</title>
</head>
<body>
    <?php require 'views/header.php'; ?>
    
    <div id="login-main">
    
        <form action="<?php echo constant('URL'); ?>/signup/newUser" method="POST">
        <div></div>
            <h2>Registrarse</h2>

            <div class="row mb-3 gx-3">
                <div class="col">
                        <input type="text" name="cedula" id="cedula" class="form-control form-control-lg" placeholder="Cedula" required>
                        
                    </div>
                </div>
                                                            
                <div class="row mb-3 gx-3">
                    <div class="col">
                        <input type="text" name="nombre" id="nombre" class="form-control form-control-lg" placeholder="Nombre" required>
                        
                    </div>
                </div>
            
                <div class="row mb-3 gx-3">
                    <div class="col">
                        <input type="text" name="apellido1" id="apellido1" class="form-control form-control-lg" placeholder="Apellido 1" required>
                        
                    </div>
                </div>

                <div class="row mb-3 gx-3">
                    <div class="col">
                        <input type="text" name="apellido2" id="apellido2" class="form-control form-control-lg" placeholder="Apellido 2" required>
                        
                    </div>
                </div>

            

            <div class="mb-3">
                <input type="tel" name="telefono" id="telefono" pattern="[0-9]{8}" placeholder="Telefono">
                
            </div>

            <div class="mb-3">
                <input type="text" name="direccion" id="direccion" class="form-control form-control-lg" placeholder="Direccion">
                
            </div>
            
            <div class="mb-3">
                <input type="text" name="cuentaBancaria" id="cuentaBancaria" class="form-control form-control-lg" placeholder="Cuenta bancaria">
                
            </div>

            <div class="mb-3">
                <input type="email" name="email" id="email" class="form-control form-control-lg" placeholder="email">
                
            </div>
            
            <div class="mb-3">
                <input type="text" name="contrasena" id="contrasena" class="form-control form-control-lg" placeholder="Password">
                
            </div>
            
            <div class="mb-3">
                <input type="text" name="confcontrasena" id="confcontrasena" class="form-control form-control-lg" placeholder="Confirme Password">
                
            </div>
            <div id="passError" ></div>

            <div class="mb-3">
                <input type="rol" name="rol" id="rol" class="form-control form-control-lg" placeholder="rol del usuario" required>
                
            </div>
            <p>
                <input type="submit" value="Agregar Usuario" />
            </p>
            
        </form>
    </div>
</body>
</html>