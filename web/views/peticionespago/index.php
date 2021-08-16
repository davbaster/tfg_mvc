<?php

    $user                       = $this->d['user'];
    $fechas                     = $this->d['fechas'];//recibe las fechas de los pagos pendientes de pago y pagados
    $peticionesPago             = $this->d['peticionesPagoRecibidas'];//recibe todos los Ids de peticionesPago (planillas) aprobadas para pago


?>

<link rel="stylesheet" href="<?php echo constant('URL') ?>/public/css/history.css">
    <?php require_once 'views/dashboard/header.php'; ?>

    <div id="main-container">
    
        <div id="history-container" class="container">
            
            <div id="history-options">
                <h2>Historial de Planillas</h2>
                <!--//TODO mejora: dividir el filtro en a;o, mes, dia  -->
                <!-- //TODO mejora: hacer que los filtros sean cruzados entre si -->
                
                <div id="filters-container">
                    <div class="filter-container">
                        <select id="sdate" class="custom-select">
                            <!-- va php code v11min51 -->
                            <option value="">Ver todas las fechas</option>
                                <?php
                                    $options = $fechas;
                                    foreach ($options as $option) {
                                        echo "<option value=$option >".$option."</option>";
                                    }
                                ?>
                        </select>
                    </div>

                    <div class="filter-container">
                        <select id="scontratista" class="custom-select">
                            <option value="">Todos los Contratistas</option>
                                <?php
                                    $options = $peticionesPago;
                                    foreach ($options as $option) {
                                        // $valor = $option['nombre'] .' '. $option['apellido1'] ;
                                        echo "<option value=$option >".$option ."</option>";
                                    }
                                ?>
                            
                        </select>
                    </div>

                    <div class="filter-container">
                        <select id="sestado" class="custom-select">
                            <option value="">Todos los estados</option>

                
                                <option value="pendiente" >Pendiente</option>;
                                <option value="pagado" >Pagado</option>;

                            
                        </select>
                    </div>
                </div>   
            </div>
            
            <div id="table-container">
                <table width="100%" cellpadding="0">
                    <thead>
                        <tr>
                        <th data-sort="id">ID</th>
                        <th data-sort="title" width="20%">Contratista</th>
                        <th data-sort="date">Fecha</th>
                        <th data-sort="amount">Cantidad</th>
                        <th data-sort="amount">Estado</th>
                        <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="databody">
                        
                    </tbody>
                </table>
            </div>
            
        </div>

    </div>

    <script src="public/js/peticionespago.js"></script>