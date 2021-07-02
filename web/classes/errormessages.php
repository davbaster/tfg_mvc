<?php

class ErrorMessages{

    // ERROR_CONTROLLER_METHOD_ACTION
    const ERROR_ADMIN_NEWCATEGORY_EXISTS = "000";
    const ERROR_SIGNUP_NEWUSER = "001";
    const ERROR_SIGNUP_NEWUSER_EMPTY = "002";
    const ERROR_SIGNUP_NEWUSER_EXISTS = "003";

    private $errorList = [];

    public function __construct(){

        $this->errorList = [
            ErrorMessages::ERROR_ADMIN_NEWCATEGORY_EXISTS => 'El nombre de la categoria ya existe',
            ErrorMessages::ERROR_SIGNUP_NEWUSER => 'Hubo un error  al intentar procesar la solicitud, el usuario ya existe',
            ErrorMessages::ERROR_SIGNUP_NEWUSER_EMPTY => 'Hubo un error  al intentar procesar la solicitud, hay campos vacio',
            ErrorMessages::ERROR_SIGNUP_NEWUSER_EXISTS => 'Hubo un error  al intentar procesar la solicitud, ya existe el numero de cedula'
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