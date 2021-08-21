<?php
    $user = $this->d['user'];
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense App - Dashboard</title>
    <link rel="stylesheet" href="<?php echo constant('URL') ?>/public/css/user.css">
</head>
<body>
    <?php require_once 'views/header.php'; ?>

    <div id="main-container">
        <?php $this->showMessages() ?>
        <div id="user-container" class="container">
            <div id="user-header">
                <div id="user-info-container">
                    <div id="user-photo">
                    
                    </div>
                    <div id="user-info">
                        <h2></h2>
                    </div>
                </div>
            </div>
            <div id="side-menu">
                <ul>
                    <li><a href="#add-user-container">Agregar Usuario</a></li>
                    <li><a href="#search-user-container">Buscar Usuario</a></li>
                    <!-- <li><a href="#budget-user-container">Presupuesto</a></li> -->
                </ul>
            </div>

            <div id="user-section-container">
                
                <section id="add-user-container">
                    <form action=<?php echo constant('URL'). '/user/crearUsuario' ?> method="POST">
                        <div class="">
                            <label for="cedula">Cedula</label>
                            <input type="text" name="cedula" id="cedula" required value=""></input>
                        </div>
                        <div class="">
                            <label for="name">Nombre</label>
                            <input type="text" name="nombre" id="nombre" required value=""></input>
                        </div>
                        <div class="">
                            <label for="name">Primer Apellido</label>
                            <input type="text" name="apellido1" id="apellido1" required value=""></input>
                        </div>
                        <div class="">
                            <label for="name">Segundo Apellido</label>
                            <input type="text" name="apellido2" id="apellido2" required value=""></input>
                        </div>
                        <div class="">
                            <label for="name">Telefono</label>
                            <input type="text" name="telefono" id="telefono" value=""></input>
                        </div>
                        <div class="">
                            <label for="name">Cuenta Bancaria</label>
                            <input type="text" name="cuentaBancaria" id="cuentaBancaria" value=""></input>
                        </div>
                        <div class="">
                            <label for="name">Direccion</label>
                            <input type="text" name="direccion" id="direccion" value=""></input>
                        </div>
                        <div id="datos_opcional" hidden>
                            <div class="">
                                <label for="name">Correo Electr&oacute;nico</label>
                                <input type="text" name="email" id="email" value=""></input>
                            </div>
                            <div class="">
                                <label for="name">Contrase&ntilde;a</label>
                                <input type="password" name="contrasena" id="contrasena" value=""></input>
                            </div>
                            <div class="">
                                <label for="">Confirmaci&oacute;n Contrase&ntilde;a</label>
                                <input type="password" name="confcontrasena" id="confcontrasena" onkeyup='verificarContrasenaIgual()' value=""></input>
                                <span id='message'></span>
                            </div>
                        </div>
                        <div class="">
                        <label for="rol">Rol del usuario:</label>
                            <select name="rol" id="rol" required>
                                <option value="construccion">Construccion</option>
                                <option value="contratista">Contratista</option>
                                <!-- <option value="contador">Contador</option> -->
                                <option value="administrador">Administrador</option>
                                
                            </select>
                            <div><input type="submit" value="Crear usuario" /></div>
                        </div>
                    </form>
                </section>

                <section id="search-user-container">
                    <form action="<?php echo constant('URL'). '/user/updatePassword' ?>" method="POST">
                        <div class="section">
                            <label for="current_password">Password actual</label>
                            <input type="password" onkeyup='check();' name="current_password" id="current_password" autocomplete="off" required>

                            <label for="new_password">Nuevo password</label>
                            <input type="password"  onkeyup='check();' name="new_password" id="new_password" autocomplete="off" required>
                            <span id='message'></span>
                            <div><input type="submit" id="btnEnviar" value="Cambiar password" /></div>
                            
                        </div>
                    </form>
                </section>

                <!-- <section id="budget-user-container">
                    <form action="user/updateBudget" method="POST">
                        <div class="section">
                            <label for="budget">Definir presupuesto</label>
                            <div><input type="number" name="budget" id="budget" autocomplete="off" required value="<?php echo $user->getBudget() ?>"></div>
                            <div><input type="submit" value="Actualizar presupuesto" /></div>
                        </div>
                    </form>
                </section> -->

            </div><!-- user section container -->
        </div><!-- user container -->

    </div><!-- main container -->
    
    <!-- <script> -->
    <script src="public/js/user_dashboard.js"></script>
    <script src="public/js/tools.js"></script> 
    
        

    
</body>
</html>