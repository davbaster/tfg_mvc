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

    $rol                        = $user->getRol();

?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Planillas - Principal</title>
    <!-- <link rel="stylesheet" href="public/bootstrap/css/bootstrap.mini.css"> -->
</head>
<body>
    <?php require 'header.php'; ?>

    <div id="main-container">
        <?php $this->showMessages() ?>
        <div id="expenses-container" class="container">
        
            <div id="left-container">
                
                <div id="expenses-summary">
                    <div>
                        <h2>Bienvenido <?php echo $user->getNombre() ?></h2>
                        <span class="total-budget-text">
                            Logueado como: <?php echo $rol ?>      
                        </span>
                    </div>
                    <div class="cards-container">
                        <div class="card w-100">
                            <div class="total-budget">
                                <span class="total-budget-text">
                                    Balance General de la Planilla     
                                </span>
                            </div>
                            <div class="total-expense">
                                
                             <?php
                                //total pagado del contrato $user->getBudget() = monto del contrato
                               if($totalThisMonth == NULL){
                                   echo 'Hubo un problema al cargar la informacion';
                               }else{?>
                                    <span class="<?php echo ($user->getBudget() < $totalThisMonth)? 'broken': '' ?>">$<?php
                                   echo number_format($totalThisMonth, 2);?>
                                    </span>
                                <?php }?>
                                
                            </div>
                        </div>
                    </div>
                    <div class="cards-container">
                        <div class="card w-50">
                            <div class="total-budget">
                                <span class="total-budget-text">
                                    de
                                    $<?php 
                                        //budget es la cantidad asignado al contrato
                                        //echo number_format($user->getBudget(),2) . ' te quedan del contrato';
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
                                                    echo "-$" . number_format(abs($user->getBudget() - $totalThisMonth), 2);
                                                }else{
                                                    echo "$" . number_format($user->getBudget() - $totalThisMonth, 2);
                                                }
                                            ?>
                                        </span> 
                                    <?php }?>
                            </div>
                        </div>
                        
                        <div class="card w-50">
                            <div class="total-budget">
                            <span class="total-budget-text">Tu gasto más grande este mes</span>
                            
                            </div>
                            <div class="total-expense">
                                <?php
                                    //peticionPago pagada mas alta en el mes
                                if($totalThisMonth == NULL){
                                    echo 'Hubo un problema al cargar la informacion';
                                }else{?>
                                        <span>$<?php
                                            echo number_format($maxExpensesThisMonth, 2);?>
                                        </span>
                                <?php }?>
                            </div>
                        </div>

                    </div>
                </div>

                <div id="chart-container" >
                    <div id="chart" >

                    </div>
                </div>

                <div id="expenses-category">
                    <h2>Planillas pendientes de aprobacion</h2>
                    <div id="categories-container">
                        <?php
                            //peticionPago pagada mas alta en el mes
                            if($petiPendientesAprobar == NULL){
                                echo 'No hay Planillas que aprobar.';
                            }else{
                                    foreach($petiPendientesAprobar as $p){ ?>
                                        <!-- Agregar un onClick para llamar a una funcion con popup -->
                                        <div class="card w-30 bs-1" style="background-color: coral" id=peticionPago>
                                            <div class="content category-name">
                                                <?php echo $p->getFechaCreacion(); ?>
                                            </div>
                                            <div class="content category-name">
                                                <!-- TODO poner el nombre y primer apellido  -->
                                                <?php echo $p->getCedula(); ?>
                                            </div>
                                            <div class="content category-name">
                                                <!-- TODO poner el nombre y primer apellido  -->
                                                <input type="hidden" name="idPeticionPago" id="idPeticionPago" value="<?php echo $p->getId() ?>">
                                            </div>

                                            
                                            <div class="title category-total">$<?php echo $p->getMonto(); ?></div>
                                        </div>



                                    <?php }?>
                        <?php }?>
                    </div>
                </div>

                <div id="expenses-category">
                    <h2>Pagos pendientes</h2>
                    <div id="categories-container">
                        <?php
                            //peticionPago pagada mas alta en el mes
                            if($pagosPendientes == NULL){
                                echo 'No hay pagos por hacer.';
                            }else{
                                    foreach($pagosPendientes as $p){ ?>

                                        <div class="card w-30 bs-1" style="background-color: coral" >
                                            <div class="content category-name">
                                                <?php echo $p['fecha']; ?>
                                            </div>
                                            <div class="content category-name">
                                                <?php echo $p['nombre'] . ' ' . $p['apellido1']; ?>
                                            </div>
                                            <div class="title category-total">$<?php echo $p['monto']; ?></div>
                                        </div>



                                    <?php }?>
                        <?php }?>
                    </div>
                </div>
            </div>

            <div id="right-container">
                <div class="transactions-container">
                    <section class="operations-container">
                        <h2>Operaciones</h2>  

                        <?php
                            //peticionPago pagada mas alta en el mes
                            if($peticionesOpen  == NULL){
                                echo 'No hay planillas abiertas.';
                        ?>
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
                                <a href="" class="btn-peticion" id="new-peticion-pago" value="">Crear Planilla<i class="material-icons">keyboard_arrow_right</i></a>
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
                    <section id="expenses-recents">
                        <h2>Planillas abiertas</h2>
                        <div id="planillasOpenContainer">
                            <?php
                                //peticionPago pagada mas alta en el mes
                                if($peticionesOpen  == NULL){
                                    echo 'No hay Planillas abiertas.';
                                }else{
                                        foreach($peticionesOpen  as $p){ ?>
                                            <!-- Agregar un onClick para llamar a una funcion con popup -->
                                            <div class="card w-30 bs-1 peticion-open-card" style="background-color: coral" id=peticionPagoOpenCard >
                                                <div class="content category-name peticion-open-fecha" id=peticionPagoOpenFecha>
                                                    <?php echo $p->getFechaCreacion(); ?>
                                                </div>
                                                <div class="content category-name peticion-open-titulo" id=peticionPagoOpenTitulo>
                                                    <!-- descripcion de la planilla  -->
                                                    <?php echo $p->getNombre(); ?>
                                                </div>
                                                <div class="content category-name peticion-open-monto" id=peticionPagoOpenMonto>¢<?php echo $p->getMonto(); ?></div>
                                                <div class="content category-name peticion-open-planilla"  id=peticionPagoOpenPlanilla> 
                                                    <!-- ID de la planilla -->
                                                    <input type="hidden" name="idPeticionPago" id="idPeticionEscondida" value="<?php echo $p->getId() ?>">
                                                </div>
                                            </div>



                                        <?php }?>
                            <?php }?>
                        </div>
                    
                    </section>


                    <!-- Seccion de pagos recientes -->
                    <section id="expenses-recents">
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
                    
                    </section>
                </div>
            </div>
            

        </div>

    </div>
    <script src="public/js/dashboard_pago.js"></script>
    <script src="public/js/dashboard_planilla.js"></script>
    <script src="public/js/dashboard_default.js"></script>
    
</body>
</html>