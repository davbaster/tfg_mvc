<?php
    

  

    require_once 'models/pagosmodel.php';
    require_once 'models/peticionespagomodel.php';
    require_once 'models/usermodel.php';

    $user                       = $this->d['user'];
    $peticionesPago             = $this->d['peticionesPago']; 
    $usuarios                   = $this->d['usuarios'];

?>



<link rel="stylesheet" href="<?php echo constant('URL'); ?>/public/css/expense.css">

<!-- FIXME arreglar los campos que se van a llenar -->
<form id="form-expense-container" action="pagos/newPago" method="POST">
    <h3>Registrar nuevo pago</h3>

    <!-- <div class="section" style="display:none;">
        <label for="cedula">Fecha de gasto</label>
        <input type="cedula" name="cedula" id="" value="<?php //echo $u->getCedula() ?>">
    </div>     -->
    <div class="section">
        <label for="trabajador">Trabajador</label>
            <select name="cedula" id="" required>
            <?php 
                foreach ($usuarios as $u) {
            ?>
                <!-- aqui nos lista las planillas / peticiones de pago que estan en estado open -->
                <?php
                //if($u->getRol() == "construccion"){ //TODO Este if deberia de verificar que tipo de rol tiene el usuario en session 
                                                  //para limitar el tipo de usuario que se despliega en la lista
                ?>
                    <option name="cedula" value="<?php echo $u->getCedula() ?>"><?php echo $u->getNombre() ." " . $u->getApellido1() ." " . $u->getApellido2() ?></option> 
                    <?php
                //    }
                }
            ?>
            </select>
    </div>    
    <div class="section">
        <label for="amount">Cantidad</label>
        <input type="number" name="amount" id="amount" autocomplete="off" required>
    </div>
       
    <div class="section">
        <label for="categoria">Planilla</label>
            <select name="peticion_pago_id" id="" required>
            <?php 
                foreach ($peticionesPago as $p) {
            ?>
                <!-- aqui nos lista las planillas / peticiones de pago que estan en estado open -->
                <?php
                if($p->getEstado() == "open"){
                ?>
                    <option name="peticionPagoID" value="<?php echo $p->getId() ?>"><?php echo $p->getNombre() ?></option> 
                    <?php
                    }
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
                if (!empty($peticionesPago)) {
                    ?>
                    <input type="submit" value="Nuevo Pago">
       
        <?php
                }else{
                    ?>
                    <input type="submit" value="Nuevo Pago" disabled>
        <?php
                }
                    ?>
    </div>
</form>