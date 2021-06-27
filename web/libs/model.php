<?php

    class Model{

        function __construct(){
            $this->db = new Database();

        }

        function query($query){
            // sentencia para el query
            return $this->db->connect()->query($query);
        }
    }

    // aqui iria el API

?>