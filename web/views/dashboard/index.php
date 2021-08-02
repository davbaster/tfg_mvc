<?php

    $user                      = $this->d['user'];
    $pagosPendientes           = $this->d['pagosPendientes'];
    $petiPendientesPagar       = $this->d['petiPendientesPagar'];
    $petiPendientesAprobar     = $this->d['petiPendientesAprobar'];

?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Planillas - Principal</title>
    <link rel="stylesheet" href="public/bootstrap/css/bootstrap.mini.css">
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
                    </div>
                    <div class="cards-container">
                        <div class="card w-100">
                            <div class="total-budget">
                                <span class="total-budget-text">
                                    Balance General del Contrato     
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
                                        echo number_format($user->getBudget(),2) . ' te quedan del contrato';
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
                    <h2>Gastos del mes por categoria</h2>
                    <div id="categories-container">
                        
                    </div>
                </div>
            </div>

            <div id="right-container">
                <div class="transactions-container">
                    <section class="operations-container">
                        <h2>Operaciones</h2>  
                        
                        <button class="btn-main" id="new-expense">
                            <i class="material-icons">add</i>
                            <span>Registrar nuevo gasto</span>
                        </button>
                        <a href="<?php echo constant('URL'); ?>user#budget-user-container">Definir presupuesto<i class="material-icons">keyboard_arrow_right</i></a>
                    </section>

                    <section id="expenses-recents">
                    <h2>Registros más recientes</h2>
                    
                    </section>
                </div>
            </div>
            

        </div>

    </div>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="public/js/dashboard.js"></script>
    
</body>
</html>