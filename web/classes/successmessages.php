<?php

class SuccessMessages{

    // SUCCESS_CONTROLLER_METHOD_ACTION
    const SUCCESS_ADMIN_NEWCATEGORY_EXISTS = "s5000";
    const SUCCESS_SIGNUP_NEWUSER = "s5010";
    const SUCCESS_USER_UPDATEPHOTO = "s5020";
    const SUCCESS_USER_UPDATEPASSWORD = "s5021";
    const SUCCESS_USER_UPDATENAME = "s5022";

    private $successList = [];

    public function __construct(){
        $this->successList = [
            SuccessMessages::SUCCESS_ADMIN_NEWCATEGORY_EXISTS => 'Todo salio bien con la categoria ingresada',
            SuccessMessages::SUCCESS_SIGNUP_NEWUSER => 'Usuario ingresado correctamente',
            SuccessMessages::SUCCESS_USER_UPDATEPASSWORD => 'Clave actualizada correctamente',
            SuccessMessages::SUCCESS_USER_UPDATEPHOTO => 'Foto actualizada correctamente',
            SuccessMessages::SUCCESS_USER_UPDATENAME => 'Nombre actualizado correctamente'
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