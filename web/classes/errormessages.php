<?php

class ErrorMessages{

    // ERROR_CONTROLLER_METHOD_ACTION
    const ERROR_ADMIN_NEWCATEGORY_EXISTS = "000";

    private $errorList = [];

    public function __construct(){

        $this->errorList = [
            ErrorMessages::ERROR_ADMIN_NEWCATEGORY_EXISTS => 'El nombre de la categoria ya existe'
        ];

    }

    // devuelve el nombre del error que calce con el hash ingresado
    public function get($hash){
        return $this->errorList[$hash];
    }

    // toma una clave y va a devolver si existe o no
    public function existKey($key){
        if(array_key_exists($key, $this->errorList)){
            return true;
        }else{
            return false;
        }
    }
}

?>