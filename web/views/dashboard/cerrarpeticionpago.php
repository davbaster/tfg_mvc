<?php
    

  

    require_once 'models/pagosmodel.php';
    require_once 'models/peticionespagomodel.php';
    require_once 'models/usermodel.php';

    $user                               = $this->d['user'];
    //$peticionesPago                     = $this->d['peticionesPagoAbiertasPorUsuario'];
    
    $peticionPago                       = $this->d['peticionAbiertaSeleccionada'];
    $pagosOpen                          = $this->d['pagosOpenPerId'];  //contiene los pagos asignados a esta peticionPago
    $usuarios                           = $this->d['usuarios'];

?>

<link rel="stylesheet" href="<?php echo constant('URL'); ?>/public/css/expense.css">

<!-- FIXME arreglar los campos que se van a llenar -->
<form id="form-expense-container" action="dashboard/enviarPeticionPago" method="POST">
    <h3>Resumen planilla</h3>
    <input id="peticionPago_id" name="peticionPago_id" value="<?php echo $peticionPago['id_planilla'] ?>" type="hidden">
    <div class="section" >
        <label for="cedula" value= "">Descripcion: <?php echo " " . $peticionPago['descripcion'] ?></label>
        
    </div>  

    <div class="section">
        <label for="amount">Monto: <?php echo " " . $peticionPago['monto'] ?></label>
        <!-- <input type="number" name="amount" id="amount" autocomplete="off" required> -->
    </div>
    <div class="section">
        <label for="detalles">Detalles:</label>
        <!-- <input type="text" name="detalles" id="detalles" required> -->
        <textarea id="detalles" name="detalles" rows="4" cols="39"><?php echo " " . $peticionPago['detalles'] ?></textarea><!-- FIXME sola guarda la primera linea de texto -->
    </div>
    <div class="section">
        <!-- aqui va a ir la lista de trabajadores que esta incluida en la planilla -->
        <label for="trabajadores">Trabajadores</label>
        <!-- <div><input type="text" name="nombre_planilla" autocomplete="off" required></div> -->
        <select name="trabajador" id="">
            <?php 
                if (!empty($pagosOpen)) {
                    //for each
                    foreach ($pagosOpen as $p) {
                        $nombre =  $p['nombre'].' '.$p['apellido1'].' '.$p['apellido2'];
            ?>
                <!-- value contiene la peticionesPagoID , getNombre = titulo de la planilla -->
                <option name="pagoId" value="<?php echo $p['id_pago'] ?>"><?php echo $nombre .' ¢'. $p['adeudado'] ?></option> 
            <?php
                    }
                 }else {
            ?>
                   <option name="pagoId" value="" disabled><?php echo "No hay trabajadores en la planilla" ?></option> 
            <?php
                 }
               
            ?>


            </select>
    </div> 
  
    
    <div class="center">
        <input type="submit" value="Eviar Planilla">
    </div>


</form>