<?php
    

  

    require_once 'models/pagosmodel.php';
    require_once 'models/usermodel.php';
    require_once 'models/peticionespagomodel.php';

    $user                       = $this->d['user'];
    $usuarios                   = $this->d['usuarios'];
    $peticionPago               = $this->d['peticionAbiertaSeleccionada'];

    $tituloPlanilla = $peticionPago['descripcion'];

?>



<link rel="stylesheet" href="<?php echo constant('URL'); ?>/public/css/expense.css">

<!-- FIXME arreglar los campos que se van a llenar -->
<form id="form-expense-container" action="<?php echo constant('URL'); ?>/prestamos/newPrestamo" method="POST">
    <h3>Registrar Adelanto de Salario</h3>

    <input id="peticionPago_id" name="peticionPago_id" value="<?php echo $peticionPago['id_planilla'] ?>" type="hidden">
    <div class="section" >
        <label for="descripcion" value= "">Planilla: <?php echo " " . $peticionPago['descripcion'] ?></label>
        
    </div>  
    <div class="section">
        <label for="trabajador">Trabajador</label>
            <select name="cedula" id="" required>
            <?php 
                foreach ($usuarios as $u) {
            ?>

                    <option name="cedula" value="<?php echo $u->getCedula() ?>"><?php echo $u->getNombre() ." " . $u->getApellido1() ." " . $u->getApellido2() ?></option> 
                    <?php
                
                }
            ?>
            </select>
    </div>    
    <div class="section">
        <label for="monto">Cantidad</label>
        <input type="number" name="monto" id="monto" autocomplete="off" required>
    </div>
       
    <div class="section">
        <label for="categoria">Planilla</label>
            <select name="peticion_pago_id" id="peticion_pago_id" required>
            <?php 
                if (!empty($peticionPago)) {
            ?>
                <!-- value contiene la peticionesPagoID , getNombre = titulo de la planilla -->
                <option name="peticionPagoID" value="<?php echo $peticionPago['id_planilla'] ?>"><?php echo $tituloPlanilla ?></option> 
            <?php
                 }else {
            ?>
                   <option name="peticionPagoID" value="<?php echo $peticionPago['id_planilla'] ?>" disabled><?php echo $tituloPlanilla ?></option> 
            <?php
                 }
               
            ?>


            </select>


    </div>
    
    <div class="section">
        <label for="detalles">Detalles</label>
        <!-- <div><input type="textarea" name="detalles" autocomplete="off" required></div> -->
        <textarea id="detalles" name="detalles" rows="4" cols="39"></textarea>
    </div>

    <div class="center">
        
        <?php 
                if (!empty($peticionPago)) {
                    ?>
                    <input type="submit" value="Pedir Adelanto">
       
        <?php
                }else{
                    ?>
                    <input type="submit" value="Pedir Adelanto" disabled>
        <?php
                }
                    ?>
    </div>
</form>