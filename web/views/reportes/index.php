<?php
    $user = $this->d['user'];
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense App - Dashboard</title>
    <link rel="stylesheet" href="<?php echo constant('URL') ?>/public/css/user.css">
    <!-- <link rel="stylesheet" href="<?php //echo constant('URL') ?>/public/css/history.css"> -->
</head>
<body>
    <?php require_once 'views/dashboard/header.php'; ?>

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
                    <li><a href="#search-user-container">Pagos por usuario</a></li>
                    <li><a href="#add-user-container">Planillas por usuario</a></li>
                    
                    <!-- <li><a href="#budget-user-container">Presupuesto</a></li> -->
                </ul>
            </div>

            <div id="user-section-container">
                
                <section id="add-user-container">
                    <form action="" method="">
                            <div class="section">
                                <label for="cedula_pagos">Busqueda por cedula:</label>
                                <input type="number" name="cedula_buscar_pagos" id="cedula_buscar_pagos" autocomplete="off">

                                <!-- <label for="new_password">Busqueda por Primer Apellido</label>
                                <input type="text" name="apellido1_buscar" id="apellido1_buscar" autocomplete="off"> -->
                                <span id='message-pagos'></span>
                                <div><input type="submit" id="btnBuscarPagos" value="buscarPagos" /></div>
                            </div>                      
                    </form>
                    <!-- Tabla de resultados -->
                    
                    <div id="table-pagos-container-right-side">
                    </div>
                    <div id="user-container-view-pagos">

                </section>

                <!-- BUSCAR usuario  -->
                <section id="search-user-container">
                    <form action="" method="">
                        <div class="section">
                            <label for="cedula">Busqueda por cedula:</label>
                            <input type="number" name="cedula_buscar_planillas" id="cedula_buscar_planillas" autocomplete="off">

                            <!-- <label for="new_password">Busqueda por Primer Apellido</label>
                            <input type="text" name="apellido1_buscar" id="apellido1_buscar" autocomplete="off"> -->
                            <span id='message-planillas'></span>
                            <div><input type="submit" id="btnBuscarPlanillas" value="Buscar" /></div>
                        </div>                      
                    </form>
                    <!-- Tabla de resultados -->
                    
                    <div id="table-container-right-side">
                        <!-- <table width="100%" cellpadding="0">
                            <thead>
                                <tr>
                                <th data-sort="id">Cedula</th>
                                <th data-sort="nombre" width="20%">Trabajador</th>
                                <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="databody">
                                
                            </tbody>
                        </table> -->
                    </div>
                    <div id="user-container-view">
                    </div>
                    <div id="user-container-edit" hidden>
                        <form action=<?php echo constant('URL'). '/user/actualizarUsuario' ?> method="POST">
                            <div id="user-edit">

                            </div>
                        <div><input type="submit" value="Guardar Cambios" /></div>
                        <div><button type="button" onclick="cancelarEdicion()">Cancelar</button></div>
                    </div>
                    

                </section>

            </div><!-- user section container -->
        </div><!-- user container -->

    </div><!-- main container -->
    
    <!-- <script> -->
    <script src="public/js/reportes_dashboard.js"></script>
    <script src="public/js/reportes_tools.js"></script> 
    
        

    
</body>
</html>