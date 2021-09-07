<?php

$user                       = $this->d['user'];
$fechas                     = $this->d['fechas'];//recibe las fechas de los pagos pendientes de pago y pagados
$peticionesPagoAutorizadas  = $this->d['peticionesPagoAutorizadas'];//recibe todos los Ids de peticionesPago (planillas) aprobadas para pago


?>

<link rel="stylesheet" href="<?php echo constant('URL') ?>/public/css/history.css">
<link rel="stylesheet" href="<?php echo constant('URL') ?>/public/css/perticionespago_dashboard.css"><!--TODO usar un mejor nombre que describa  -->

<?php require_once 'views/dashboard/header.php'; ?>

<div id="main-container">
    <div id="user-container" class="container">

        <button class="tablink" onclick="openPage('Adelantos', this, 'red') " id="defaultOpen">Prestamos </button>
        <!-- <button class="tablink" onclick="openPage('News', this, 'green')" >Pendientes</button>
        <button class="tablink" onclick="openPage('Contact', this, 'blue')">Aprobadas</button> -->
        <button class="tablink" onclick="openPage('Historial', this, 'orange')">Planillas</button>

        <!-- ADELANTOS/PRESTAMOS SECTION -->
        <div id="Adelantos" class="tabcontent">
                  
            <div id="history-container" class="container">
                
                <div id="history-options">
                    <h2>Prestamos autorizados para pago</h2>
                </div>
                
                <div id="table-container" class="table-container">
                    <table width="100%" cellpadding="0">
                        <thead>
                            <tr>
                            <th >ID Prestamo</th>
                            <th data-titulo="Contratista:" width="20%">Trabajador</th>
                            <th data-titulo="Fecha:">Fecha</th>
                            <th data-titulo="Monto:">Monto</th>
                            <th data-titulo="Estado:">Estado</th>
                            <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="databody-prestamos">
                            
                        </tbody>
                    </table>
                </div>
                
            </div>
                    
        </div><!--FIN DE ADELANTOS DE PAGO/PRESTAMOS  -->
            

        <div id="News" class="tabcontent">
        <h3>Planillas pendientes de revision</h3>
        <p>Some news this fine day!</p> 
        </div>

        <div id="Contact" class="tabcontent">
        <h3>Planillas Aprobadas</h3>
        <p>Get in touch, or swing by for a cup of coffee.</p>
        </div>

        <!-- PAGINA PAGOS PLANILLAS AUTORIZADAS PARA PAGO-->
        <div id="Historial" class="tabcontent">
                     
            <div id="history-container" class="container">
                
                <div id="history-options">
                    <h2>Planillas autorizadas para pago</h2>
                    <!--//TODO mejora: dividir el filtro en a;o, mes, dia  -->
                    <!-- //TODO mejora: hacer que los filtros sean cruzados entre si -->
                    
                    <div id="filters-container">
                    <div class="filter-container">
                        <select id="sdate-pagos-autorizados" class="custom-select">
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
                        <select id="speticionpago-pagos-autorizados" class="custom-select">
                            <option value="">Ver todas las planillas</option>
                                <?php
                                    $options = $peticionesPagoAutorizadas;
                                    foreach ($options as $option) {
                                        echo "<option value=$option >".$option."</option>";
                                    }
                                ?>
                            
                        </select>
                    </div>
                </div>   
            </div>
            
            <div id="table-container">
                <table width="100%" cellpadding="0">
                    <thead>
                        <tr>
                        <th data-sort="id">ID</th>
                        <th data-sort="title" width="20%">Trabajador</th>
                        <th data-sort="category">Planilla</th>
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
            
                                        
        </div><!--FIN DE PAGINA PAGOS PLANILLAS AUTORIZADAS PARA PAGO-->

        

        </div>

    </div>
</div>





<script src="public/js/pagos_dashboard.js"></script>
<script src="public/js/pagos_prestamos.js"></script> 
<script src="public/js/pagos.js"></script>
