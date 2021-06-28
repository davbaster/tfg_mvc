<?php

    // especifica el nombre de las funciones, y los parametros que tienen que llevar esas funciones
    interface IModel {
        public function save();
        public function getAll();
        public function get($id);
        public function delete($id);
        public function update();
        public function from($array);

    }
?>
