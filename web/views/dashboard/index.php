<?php

    
    require_once 'models/pagosmodel.php';
    require_once 'models/peticionespagomodel.php';

    $user                      = $this->d['user'];
    
    $pagosPendientes           = $this->d['pagosPendientes'];
    $pagosRecientes            = $this->d['pagosRecientes'];

    $petiPendientesPagar       = $this->d['petiPendientesPagar'];
    $petiPendientesAprobar     = $this->d['petiPendientesAprobar'];


    $petiRecientes            = $this->d['petiRecientes'];
    $peticionesOpen            = $this->d['peticionesOpen'];
    $peticionEstadistica       = $this->d['peticionEstadistica'];


    $prestamosRechazados        = $this->d['prestamosRechazados'];

    $rol                        = $user->getRol();

?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Planillas - Principal</title>

</head>
<body>
    <?php require 'header.php'; ?>


    <!-- INICIO MAIN CONTAINER -->
    <div id="main-container">
        <?php $this->showMessages() ?>


        <!-- FINAL STRUCTURE CONTAINER -->
        <div id="structure-container" class="container">
        
            <!-- INICIO LEFT CONTAINER -->
            <div id="left-container">
                
                <!-- INICIO Planillas/peticionPago seccion informacion  -->
                <div id="planillas-summary">
                    <div>
                        <h2>Bienvenido <?php echo $user->getNombre() ?></h2>
                        <span class="total-amount-text">
                            Logueado como: <?php echo $rol ?>      
                        </span>
                    </div>
                    <div class="cards-container">
                        <div class="card w-50">
                            <div class="total-amount">
                                <span class="total-amount-text">
                                    Balance General de la Planilla     
                                </span>
                            </div>
                            <div class="total-expense">
                                
                             <?php
                                //total pagado del contrato $user->getBudget() = monto del contrato
                               if(empty($peticionesOpen) || ($peticionesOpen[0]->getMonto() == NULL )  ){
                                   echo 'No hay planillas seleccionadas';
                               }else{?>
                                    <span class="<?php echo ($peticionesOpen[0]->getMonto() < $peticionEstadistica[0]['montoTotal']  )? 'Sobregirado': '' ?>">¢<?php
                                   echo number_format($peticionEstadistica[0]['montoTotal'] , 2);?>
                                    </span>
                                <?php }?>
                                
                            </div>
                        </div>
                    </div>
                    <div class="cards-container">
                        <div class="card w-50">
                            <div class="total-budget">
                                <span class="total-amount-text">
                                    de
                                    ¢<?php 
                                        //cantidad asignado al contrato
                                        if (!empty($peticionesOpen)) {
                                            echo  $peticionesOpen[0]->getMonto();
                                        } else {
                                            # No hay planilla seleccionada
                                            echo  "0";
                                        }
                                        
                                        
                                    ?>
                                </span>
                            </div>
                            <div class="total-expense">
                                <?php
                                    
                                    //total pagado del contrato $user->getBudget() = monto del contrato
                                if($totalThisMonth == NULL){
                                    echo 'Hubo un problema al cargar la informacion';
                                }else{?>
                                        <span>
                                            <?php
                                                $gap = $user->getBudget() - $totalThisMonth;
                                                if ($gap < 0) {
                                                    echo "-¢" . number_format(abs($user->getBudget() - $totalThisMonth), 2);
                                                }else{
                                                    echo "¢" . number_format($user->getBudget() - $totalThisMonth, 2);
                                                }
                                            ?>
                                        </span> 
                                    <?php }?>
                            </div>
                        </div>
                        
                        <div class="card w-50">
                            <div class="total-budget">
                            <span class="total-amount-text">Tu gasto más grande este mes</span>
                            
                            </div>
                            <div class="total-expense">
                                <?php
                                    //peticionPago pagada mas alta en el mes
                                if($totalThisMonth == NULL){
                                    echo 'Hubo un problema al cargar la informacion';
                                }else{?>
                                        <span>¢<?php
                                            echo number_format($maxExpensesThisMonth, 2);?>
                                        </span>
                                <?php }?>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- FINAL Planillas/peticionPago seccion informacion  -->


                <div id="chart-container" >
                    <div id="chart" >

                    </div>
                </div>


                <!-- INICIO Seccion Planillas/peticionPago PENDIENTES DE APROBAR  -->
                <div id="display-information-category">
                    <h2>Planillas pendientes de aprobacion</h2>
                    <div id="categories-container">
                        <?php
                            //peticionPago pagada mas alta en el mes
                            if($petiPendientesAprobar == NULL){
                                echo 'No hay Planillas que aprobar.';
                            }else{
                                    foreach($petiPendientesAprobar as $p){ ?>
                                        <!-- Agregar un onClick para llamar a una funcion con popup -->
                                        <div class="card card-normal w-30 bs-1" id=peticionPago>
                                            <div class="content category-name">
                                                <?php echo $p->getFechaCreacion(); ?>
                                            </div>
                                            <div class="content category-name">
                                                <!-- TODO poner el nombre y primer apellido  -->
                                                <?php echo $p->getNombre() . " ". $p->getApellido1(); ?>
                                            </div>
                                            <div class="content category-name">
                                                <!-- TODO poner el nombre y primer apellido  -->
                                                <input type="hidden" name="idPeticionPago" id="idPeticionPago_<?php echo $p->getPeticionPagoId() ?>" value="<?php echo $p->getPeticionPagoId() ?>">
                                            </div>

                                            
                                            <div class="title category-total">¢<?php echo $p->getMonto(); ?></div>
                                        </div>



                                    <?php }?>
                        <?php }?>
                    </div>
                </div>
                <!-- FINAL Seccion Planillas/peticionPago PENDIENTES DE APROBAR  -->


                <!-- INICIO Seccion PAGOS PENDIENTES DE APROBAR  -->
                <div id="display-information-category">
                    <h2>Pagos pendientes</h2>
                    <div id="categories-container">
                        <?php
                            //peticionPago pagada mas alta en el mes
                            if($pagosPendientes == NULL){
                                echo 'No hay pagos por hacer.';
                            }else{
                                    foreach($pagosPendientes as $p){ ?>

                                        <div class="card card-normal w-30 bs-1" >
                                            <div class="content category-name">
                                                <?php echo $p['fecha']; ?>
                                            </div>
                                            <div class="content category-name">
                                                <?php echo $p['nombre'] . ' ' . $p['apellido1']; ?>
                                            </div>
                                            <div class="title category-total">¢<?php echo $p['monto']; ?></div>
                                        </div>



                                    <?php }?>
                        <?php }?>
                    </div>
                </div>
                <!-- FINAL Seccion PAGOS PENDIENTES DE APROBAR  -->



                <!-- INICIO Seccion PRESTAMOS RECHAZADOS  -->
                <div id="display-information-category">
                    <h2>Prestamos rechazados</h2>
                    <div id="categories-container">
                        <?php
                            //peticionPago pagada mas alta en el mes
                            if($prestamosRechazados[0]["cedula"] == "empty"){
                                echo 'No hay prestamos rechazados por mostrar.';
                            }else{
                                    foreach($prestamosRechazados as $p){ ?>

                                        <div class="card card-error w-30 bs-1" >
                                            <div class="content category-name">
                                                <?php echo $p['fecha_creacion']; ?>
                                            </div>
                                            <div class="content category-name">
                                                <?php echo $p['nombre'] . ' ' . $p['apellido1']; ?>
                                            </div>
                                            <div class="title category-total">¢<?php echo $p['monto']; ?></div>
                                        </div>



                                    <?php }?>
                        <?php }?>
                    </div>
                </div>
                <!-- FINAL Seccion PRESTAMOS RECHAZADOS  -->



            </div>
            <!-- FINAL LEFT CONTAINER -->


            <!-- INICIO RIGHT CONTAINER -->
            <div id="right-container">
                <div class="transactions-container">
                    <section class="operations-container">
                        <h2>Operaciones</h2>  

                        <?php
                            //peticionPago pagada mas alta en el mes
                            if($peticionesOpen  == NULL){ ?>
                                 
                                 <!-- <div class="center">No hay planillas abiertas.</div> -->
                        
                            <div>
                                <a href="" class="btn-peticion" id="new-peticion-pago" value="">Crear Planilla<i class="material-icons">keyboard_arrow_right</i></a>
                            </div>
                        <?php
                            }else{
                        ?>

                            <!-- value contiene el id de planilla activa -->
                            <button class="btn-main" id="new-pago" value="<?php echo  $peticionesOpen[0]->getId() ?>">
                                <i class="material-icons">add</i>
                                <span>Nuevo Pago</span>
                            </button>

                            <div>
                                <a href="" class="btn-prestamo" id="new-prestamo" value="<?php echo  $peticionesOpen[0]->getId() ?>">Adelanto Pago<i class="material-icons">keyboard_arrow_right</i></a>
                            </div>
                            
                            <div>
                                <a href="" class="btn-peticion" id="new-peticion-pago" value="">Crear Planilla<i class="material-icons">keyboard_arrow_right</i></a>
                            </div>

                            <div>
                                <a href="" class="btn-peticion" id="modify-peticion-pago" value="<?php echo  $peticionesOpen[0]->getId() ?>">Modificar Planilla<i class="material-icons">keyboard_arrow_right</i></a>
                            </div>

                            <div>
                                <a href="" class="btn-peticion" id="cerrar-peticion-pago" value="<?php echo  $peticionesOpen[0]->getId() ?>">Cerrar Planilla<i class="material-icons">keyboard_arrow_right</i></a>
                                <!-- <a href="" class="btn-peticion" id="cerrar-peticion-pago" value="">Cerrar Planilla<i class="material-icons">keyboard_arrow_right</i></a> -->
                            </div>
                        <?php
                            }
                        ?>  
                        
                    </section>

                    <!-- Seccion de peticionesPago en estado OPEN -->
                    <section id="planillas-recents">
                        <h2>Planillas abiertas</h2>
                        <div id="planillasOpenContainer">
                            <?php
                                //peticionPago pagada mas alta en el mes
                                if($peticionesOpen  == NULL){
                                    echo '<div class="center">No hay planillas abiertas.</div>';
                                }else{
                                        foreach($peticionesOpen  as $p){ 
                                            //Agregar un onClick para llamar a una funcion con popup
                                            if  ($peticionesOpen[0]->getId() == $p->getId()){
                                        ?>

                                                <div class="card card-active w-30 bs-1 peticion-open-card" id=<?php echo "peticionPagoOpenCard" . $p->getId() ?> >
                                                    <div class="content category-name peticion-open-fecha" id=<?php echo "peticionPagoOpenFecha" . $p->getId() ?>>   
                                                        <?php echo $p->getFechaCreacion(); ?>
                                                    </div>
                                                    <div class="content category-name peticion-open-titulo" id=<?php echo "peticionPagoOpenTitulo" . $p->getId() ?>> 
                                                        <!-- descripcion de la planilla  -->
                                                        <?php echo $p->getDescripcion(); ?>
                                                    </div>
                                                    <div class="content category-name peticion-open-monto" id=<?php echo "peticionPagoOpenMonto" . $p->getId() ?>>¢<?php echo $p->getMonto(); ?></div> 
                                                    <div class="content category-name peticion-open-planilla"  id=<?php echo "peticionPagoOpenPlanilla" . $p->getId() ?>>   
                                                        <!-- ID de la planilla -->
                                                        <input type="hidden" name="idPeticionPago" id="<?php echo "idPeticionEscondida" . $p->getId() ?>" value="<?php echo $p->getId() ?>"> 
                                                    </div>
                                                </div>
                                        <?php
                                            }else {
                                        ?>
                                            <div class="card card-normal w-30 bs-1 peticion-open-card" id=<?php echo "peticionPagoOpenCard" . $p->getId() ?> >
                                                <div class="content category-name peticion-open-fecha" id=<?php echo "peticionPagoOpenFecha" . $p->getId() ?>>   
                                                        <?php echo $p->getFechaCreacion(); ?>
                                                    </div>
                                                    <div class="content category-name peticion-open-titulo" id=<?php echo "peticionPagoOpenTitulo" . $p->getId() ?>> 
                                                        <!-- descripcion de la planilla  -->
                                                        <?php echo $p->getDescripcion(); ?>
                                                    </div>
                                                    <div class="content category-name peticion-open-monto" id=<?php echo "peticionPagoOpenMonto" . $p->getId() ?>>¢<?php echo $p->getMonto(); ?></div> 
                                                    <div class="content category-name peticion-open-planilla"  id=<?php echo "peticionPagoOpenPlanilla" . $p->getId() ?>>   
                                                        <!-- ID de la planilla -->
                                                        <input type="hidden" name="idPeticionPago" id="<?php echo "idPeticionEscondida" . $p->getId() ?>" value="<?php echo $p->getId() ?>"> 
                                                    </div>
                                            </div>
                                        
                                        <?php        
                                            } 
                                        ?>  




                                        <?php }?>
                            <?php }?>
                        </div>
                    
                    </section><!-- FINAL Seccion de peticionesPago en estado OPEN -->


                    <!-- INICIO Seccion de pagos recientes -->
                    <section id="pagos-recents">
                    <h2>Pagos recientes</h2>
                    <?php
                        if($pagosRecientes == NULL){
                            echo 'No hay pagos recientes.';
                        }else{
                                foreach($pagosRecientes as $p){ ?>

                                    <div class="preview-expense">
                                        <div class="left">
                                            <div class="expense-date"><?php echo $p['fecha']; ?></div>
                                            <div class="expense-date"><?php echo $p['nombre'] . ' ' . $p['apellido1']; ?></div>
                                        </div>
                                        <div class="right">
                                            <div class="expense-amount">$<?php echo $p['monto']; ?></div>
                                        </div>
                                    </div>



                                <?php }?>
                        <?php }?>
                    
                    </section><!-- FINAL Seccion de pagos recientes -->
                </div>



            </div>
            <!-- FINAL RIGHT CONTAINER -->
            

        </div>
        <!-- FINAL DATA CONTAINER -->

    </div>
    <!-- FINAL MAIN CONTAINER -->


    <script src="public/js/dashboard_pago.js"></script>
    <script src="public/js/dashboard_planilla.js"></script>
    <script src="public/js/dashboard_default.js"></script>
    <script src="public/js/dashboard_prestamos.js"></script>
    
</body>
</html>