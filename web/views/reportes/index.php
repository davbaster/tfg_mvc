<?php
    $user = $this->d['user'];
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes - Dashboard</title>
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
                    <li><a href="#pagos-search-container">Pagos por usuario</a></li>
                    <li><a href="#planillas-search-container">Planillas por usuario</a></li>
                    
                    <!-- <li><a href="#budget-user-container">Presupuesto</a></li> -->
                </ul>
            </div>

            <div id="user-section-container">

                 <!-- BUSCAR Pagos de usuario  -->
                <!-- <section id="search-user-container"> -->
                <section id="pagos-search-container">
                    <form action="" method="">
                        <div class="section">
                            <label for="cedulaPagos">Busqueda por cedula:</label>
                            <input type="number" name="cedula_buscar_pagos" id="cedula_buscar_pagos" autocomplete="off">

                            <!-- <label for="new_password">Busqueda por Primer Apellido</label>
                            <input type="text" name="apellido1_buscar" id="apellido1_buscar" autocomplete="off"> -->
                            <span id='message-pagos'></span>
                            <div><input type="submit" id="btnBuscarPagos" value="Buscar Pagos" /></div>
                        </div>                      
                    </form>
                    <!-- Tabla de resultados -->
                    <div id="table-pagos-container-right-side"></div>
                    <div id="pagos-container-view">
                    </div>
                    
                    

                </section>

                <!-- BUSCAR Planillas de usuario  -->
                <!-- <section id="add-user-container"> -->
                <section id="planillas-search-container">
                    <form action="" method="">
                            <div class="section">
                                <label for="cedula_planillas">Busqueda por cedula:</label>
                                <input type="number" name="cedula_buscar_planillas" id="cedula_buscar_planillas" autocomplete="off">

                                <!-- <label for="new_password">Busqueda por Primer Apellido</label>
                                <input type="text" name="apellido1_buscar" id="apellido1_buscar" autocomplete="off"> -->
                                <span id='message-planillas'></span>
                                <div><input type="submit" id="btnBuscarPlanillas" value="Buscar Planillas" /></div>
                            </div>                      
                    </form>
                    <!-- Tabla de resultados -->
                    
                    <div id="table-planillas-container-right-side">
                    </div>
                    <div id="planillas-container-view"></div>

                </section>

               

            </div><!-- user section container -->
        </div><!-- user container -->

    </div><!-- main container -->
    
    <!-- <script> -->
    <script src="public/js/reportes_dashboard.js"></script>
    <script src="public/js/reportes_pagos_tools.js"></script>
    <script src="public/js/reportes_planillas_tools.js"></script> 
    
        

    
</body>
</html>