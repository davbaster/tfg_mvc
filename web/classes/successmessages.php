<?php

class SuccessMessages{

    // SUCCESS_CONTROLLER_METHOD_ACTION
    const SUCCESS_ADMIN_NEWCATEGORY_EXISTS = "s5000";
    const SUCCESS_USER_NEWUSER = "s5010";
    const SUCCESS_USER_UPDATEPHOTO = "s5020";
    const SUCCESS_USER_UPDATEPASSWORD = "s5021";
    const SUCCESS_USER_UPDATENAME = "s5022";
    const SUCCESS_ADMIN_NEWPETICIONPAGO = "s5023"; 
    const SUCCESS_PAGOS_NEWPAGO = "s5024"; 
    const SUCCESS_PETICIONPAGOS_NEWPETICION = "s5025";
    const SUCCESS_PETICIONPAGOS_DELETE = "s5026";
    const SUCCESS_PETICIONPAGO_AUTORIZAR = "s5027";
    const SUCCESS_PETICIONPAGO_ENVIAR = "s5028";
    const SUCCESS_PETICIONPAGO_RECHAZAR = "s5029";
    const SUCCESS_USER_UPDATEUSER       = "s5030";
    const SUCCESS_USER_DISABLED         = "s5031";


    private $successList = [];

    public function __construct(){
        $this->successList = [
            SuccessMessages::SUCCESS_ADMIN_NEWCATEGORY_EXISTS => 'Todo salio bien con la categoria ingresada',
            SuccessMessages::SUCCESS_USER_NEWUSER => 'Usuario creado correctamente',
            SuccessMessages::SUCCESS_USER_UPDATEPASSWORD => 'Clave actualizada correctamente',
            SuccessMessages::SUCCESS_USER_UPDATEPHOTO => 'Foto actualizada correctamente',
            SuccessMessages::SUCCESS_USER_UPDATENAME => 'Nombre actualizado correctamente',
            SuccessMessages::SUCCESS_ADMIN_NEWPETICIONPAGO => 'Peticion de pago creada satisfactoriamente',
            SuccessMessages::SUCCESS_PAGOS_NEWPAGO => 'Pago creado satisfactoriamente',
            SuccessMessages::SUCCESS_PETICIONPAGOS_NEWPETICION => 'Nueva planilla creada satisfactoriamente',
            SuccessMessages::SUCCESS_PETICIONPAGOS_DELETE => 'Peticion de pago borrada satisfactoriamente',
            SuccessMessages::SUCCESS_PETICIONPAGO_AUTORIZAR => 'Planilla autorizada satisfactoriamente',
            SuccessMessages::SUCCESS_PETICIONPAGO_ENVIAR => 'Planilla enviada para revision satisfactoriamente',
            SuccessMessages::SUCCESS_PETICIONPAGO_RECHAZAR => 'Planilla rechazada satisfactoriamente',
            SuccessMessages::SUCCESS_USER_UPDATEUSER => 'Usuario actualizado satisfactoriamente',
            SuccessMessages::SUCCESS_USER_DISABLED => 'Usuario desactivado satisfactoriamente'
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