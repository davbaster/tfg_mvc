<?php
    $peticionesPago = $this->d['peticionesPago'];
?>

<link rel="stylesheet" href="<?php echo constant('URL'); ?>/public/css/expense.css">

<!-- FIXME arreglar los campos que se van a llenar -->
<form id="form-expense-container" action="peticionespago/newPeticionPago" method="POST">
    <h3>Registrar nueva Planilla</h3>
    <div class="section">
        <label for="amount">Cantidad</label>
        <input type="number" name="amount" id="amount" autocomplete="off" required>
    </div>
    <div class="section">
        <label for="title">Descripción</label>
        <div><input type="text" name="title" autocomplete="off" required></div>
    </div>
    
    <div class="section">
        <label for="date">Fecha de gasto</label>
        <input type="date" name="date" id="" required>
    </div>    

    <div class="section">
        <label for="categoria">Categoria</label>
            <select name="category" id="" required>
            <?php 
                foreach ($peticionesPago as $p) {
            ?>
                <option value="<?php echo $p->getId() ?>"><?php echo $p->getName() ?></option>
                    <?php
                }
            ?>
            </select>
    </div>    

    <div class="center">
        <input type="submit" value="Nuevo expense">
    </div>
</form>