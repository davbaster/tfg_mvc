<?php
    

  

    require_once 'models/pagosmodel.php';
    require_once 'models/peticionespagomodel.php';
    require_once 'models/usermodel.php';

    $user                               = $this->d['user'];
    $peticionesPago                     = $this->d['peticionesPagoAbiertasPorUsuario'];
    $pagosOpen                          = $this->d['pagosOpen'];  
    $usuarios                           = $this->d['usuarios'];

?>

<link rel="stylesheet" href="<?php echo constant('URL'); ?>/public/css/expense.css">

<!-- FIXME arreglar los campos que se van a llenar -->
<form id="form-expense-container" action="peticionespago/newPeticionPago" method="POST">
    <h3>Resumen planilla</h3>
    <div class="section" >
        <label for="cedula" value= "">Descripcion: <?php echo " " . $cedulaPeticion ?></label>
        
    </div>  

    <div class="section">
        <label for="amount">Monto</label>
        <input type="number" name="amount" id="amount" autocomplete="off" required>
    </div>
    <div class="section">
        <label for="nombre_planilla">Nombre de la Planilla</label>
        <div><input type="text" name="nombre_planilla" autocomplete="off" required></div>
    </div>
    <div class="section">
        <label for="detalles">detalles</label>
        <!-- <input type="text" name="detalles" id="detalles" required> -->
        <textarea id="detalles" name="detalles" rows="4" cols="39"></textarea><!-- FIXME sola guarda la primera linea de texto -->
    </div> 
  
    
    <div class="center">
        <input type="submit" value="Eviar Planilla">
    </div>


</form>