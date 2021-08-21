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
                    <li><a href="#info-user-container">Personalizar usuario</a></li>
                    <li><a href="#password-user-container">Password</a></li>
                    <!-- <li><a href="#budget-user-container">Presupuesto</a></li> -->
                </ul>
            </div>

            <div id="user-section-container">
                
                <section id="info-user-container">
                    <form action=<?php echo constant('URL'). '/user/updateName' ?> method="POST">
                        <div class="section">
                            <label for="name">Nombre</label>
                            <input type="text" name="name" id="name" autocomplete="off" required value="<?php echo $user->getNombre() ?>">
                            <div><input type="submit" value="Cambiar nombre" /></div>
                        </div>
                    </form>

                    <form action="<?php echo constant('URL'). '/user/updatePhoto' ?>" method="POST" enctype="multipart/form-data">
                        <div class="section">
                            <label for="photo">Foto de perfil</label>
                            <?php
                                if(!empty($user->getFoto())){
                            ?>

                                <img src="<?php echo constant('URL') ?>/public/img/photos/<?php echo $user->getFoto() ?>" width="50" height="50" />
                            <?php
                                }
                            ?>
                            <input type="file" name="photo" id="photo" autocomplete="off" required>
                            <div><input type="submit" value="Cambiar foto de perfil" /></div>
                        </div>
                    </form>
                </section>

                <section id="password-user-container">
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
    <script src="public/js/configuracion_dashboard.js"></script>
    <script src="public/js/tools.js"></script> 
    
        

    
</body>
</html>