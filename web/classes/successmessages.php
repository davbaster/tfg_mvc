<?php

class SuccessMessages{

    // SUCCESS_CONTROLLER_METHOD_ACTION
    const SUCCESS_ADMIN_NEWCATEGORY_EXISTS = "500";
    const SUCCESS_SIGNUP_NEWUSER = "501";

    private $successList = [];

    public function __construct(){
        $this->successList = [
            SuccessMessages::SUCCESS_ADMIN_NEWCATEGORY_EXISTS => 'Todo salio bien con la categoria ingresada',
            SuccessMessages::SUCCESS_SIGNUP_NEWUSER => 'Usuario ingresado correctamente'
        ];
    }

    // devuelve el nombre del error que calce con el hash ingresado
    public function get($hash){
        return $this->successList[$hash];
    }

    // toma una clave y va a devolver si existe o no
    public function existKey($key){
        if(array_key_exists($key, $this->successList)){
            return true;
        }else{
            return false;
        }
    }
}

?>