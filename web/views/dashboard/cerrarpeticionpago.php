<?php
    

  

    require_once 'models/pagosmodel.php';
    require_once 'models/peticionespagomodel.php';
    require_once 'models/usermodel.php';

    $user                               = $this->d['user'];
    //$peticionesPago                     = $this->d['peticionesPagoAbiertasPorUsuario'];
    
    $peticionPago                       = $this->d['peticionAbiertaSeleccionada'];
    $pagosOpen                          = $this->d['pagosOpenPerId'];  //contiene los pagos asignados a esta peticionPago 
    $prestamos                          = $this->d['prestamosPerId'];
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
        <label for="trabajadores">Trabajadores a pagar:</label>
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
                   <option name="pagoId" value="" ><?php echo "Necesita un pago para enviar la planilla" ?></option>

            <?php
                 }
               
            ?>


            </select>
    </div>
    
    <!-- SECCION LISTA DE PRESTAMOS -->
    <div class="section">
        <!-- aqui va a ir la lista de trabajadores que esta incluida en la planilla -->
        <label for="prestamos">Prestamos en esta planilla:</label>
        <!-- <div><input type="text" name="nombre_planilla" autocomplete="off" required></div> -->
        <select name="prestamo" id="">
            <?php 
                if (!empty($prestamos)) {
                    //for each
                    foreach ($prestamos as $p) {
                        $nombre =  $p['nombre'].' '.$p['apellido1'].' '.$p['apellido2'];

                        $prestamoPendientesPago = ( ($p['estado'] == "autorizado") || ($p['estado'] == "pendiente")  ) ? true : false ;

            ?>

                        <!-- value contiene la peticionesPagoID , getNombre = titulo de la planilla -->
                        <option name="prestamoId" value="<?php echo $p['id_prestamo'] ?>"><?php echo $nombre .' ¢'. $p['monto'].' Estado:'. $p['estado'] ?></option> 
            <?php
                    }
                 }else {
            ?>
                   <option name="prestamoId" value=""><?php echo "No hay prestamos en la planilla" ?></option>

            <?php
                 }
               
            ?>


            </select>
    </div> 
  
    <!-- SECCION BOTON ENVIAR -->
    <?php 
        if (!empty($pagosOpen) & (!$prestamoPendientesPago)) {
    ?>
            <!--Debe de haber al menos un pago en la planilla para poder cerrarse  -->
            <!--No debe de haber prestamos pendientes de aprobacion  -->
            <div class="center">
                <input type="submit" value="Eviar Planilla">
            </div>
    <?php
            }else {

                
    ?>          <!-- Deshabilitar boton de enviar -->
                <div class="center">
                    <input type="submit" value="Eviar Planilla" disabled> 
                </div>
                <div class="center">
                    <p>Debe de existir al menos un pago, y todos los prestamos deben de estar pagados para poder enviar la planilla.</p>
                </div>
    <?php
                   
            }     
    ?>
    



</form>