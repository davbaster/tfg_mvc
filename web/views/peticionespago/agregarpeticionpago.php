<?php
      require_once 'models/pagosmodel.php';
      require_once 'models/peticionespagomodel.php';
      require_once 'models/usermodel.php';
  
      $user                       = $this->d['user'];//usuario en session
      //$peticionesPago             = $this->d['peticionesPago']; //TODO objeto que me permita ver quien es el usuario en session
      $cedulaPeticion = $user->getCedula();

?>

<link rel="stylesheet" href="<?php echo constant('URL'); ?>/public/css/expense.css">

<!-- FIXME arreglar los campos que se van a llenar -->
<form id="form-expense-container" action="peticionespago/newPeticionPago" method="POST">
    <h3>Crear Nueva Planilla</h3>
    <div class="section" >
        <label for="cedula" value= "<?php $cedulaPeticion ?>">Codigo de usuario: <?php echo " " . $cedulaPeticion ?></label>
        
        <input type="hidden" name="cedula" id="cedula" value="<?php $cedulaPeticion ?>">
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
        <input type="submit" value="Crear Planilla">
    </div>



    <!-- <div class="section">
        <label for="categoria">Categoria</label>
            <select name="category" id="" required>
            <?php 
                //foreach ($peticionesPago as $p) {
            ?>
                <option value="<?php //echo $p->getId() ?>"><?php //echo $p->getName() ?></option>
                    <?php
                //}
            ?>
            </select>
    </div>     -->


</form>