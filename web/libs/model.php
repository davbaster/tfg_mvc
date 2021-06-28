<?php

    include_once 'libs/imodel.php';
    class Model{

        function __construct(){
            $this->db = new Database();

        }

        function query($query){
            // sentencia para el query
            return $this->db->connect()->query($query);
        }

        //evitar escribir  $this->db->connect()->
        function prepare($query){
            return $this->db->connect()->prepare($query);
        }
    }

    // aqui iria el API

?>