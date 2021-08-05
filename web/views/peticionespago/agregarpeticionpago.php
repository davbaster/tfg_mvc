<?php
    $peticionesPago = $this->d['peticionespago'];
?>

<link rel="stylesheet" href="<?php echo constant('URL'); ?>/public/css/expense.css">

<!-- FIXME arreglar los campos que se van a llenar -->
<form id="form-expense-container" action="peticionespago/newPeticionPago" method="POST">
    <h3>Crear Nueva Planilla</h3>
    <div class="section">
        <label for="amount">Monto</label>
        <input type="number" name="amount" id="amount" autocomplete="off" required>
    </div>
    <div class="section">
        <label for="title">Nombre de la Planilla</label>
        <div><input type="text" name="title" autocomplete="off" required></div>
    </div>
    
    <div class="section">
        <label for="detalles">detalles</label>
        <input type="text" name="detalles" id="detalles" required>
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

    <div class="center">
        <input type="submit" value="nueva_peticion">
    </div>
</form>