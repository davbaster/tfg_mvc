<?php

class ErrorMessages{

    // ERROR_CONTROLLER_METHOD_ACTION
    const ERROR_ADMIN_NEWCATEGORY_EXISTS = "e000";
    const ERROR_SIGNUP_NEWUSER = "e001";
    const ERROR_SIGNUP_NEWUSER_EMPTY = "e002";
    const ERROR_SIGNUP_NEWUSER_EXISTS = "e003";
    const ERROR_LOGIN_AUTHENTICATE_EMPTY = "e004";
    const ERROR_LOGIN_AUTHENTICATE_DATA = "e005";
    const ERROR_LOGIN_AUTHENTICATE = "e006";
    const ERROR_USER_UPDATEPHOTO = "e007"; 
    const ERROR_USER_UPDATEPHOTO_FORMAT = "e008"; 
    const ERROR_USER_UPDATEPASSWORD = "e009";
    const ERROR_USER_UPDATEPASSWORD_THESAME = "e0010";
    const ERROR_USER_UPDATENAME = "e0011";
    private $errorList = [];

    public function __construct(){

        $this->errorList = [
            ErrorMessages::ERROR_ADMIN_NEWCATEGORY_EXISTS => 'El nombre de la categoria ya existe',
            ErrorMessages::ERROR_SIGNUP_NEWUSER => 'Hubo un error al intentar procesar la solicitud, el usuario ya existe',
            ErrorMessages::ERROR_SIGNUP_NEWUSER_EMPTY => 'Hubo un error al intentar procesar la solicitud, hay campos vacio',
            ErrorMessages::ERROR_SIGNUP_NEWUSER_EXISTS => 'Hubo un error al intentar procesar la solicitud, ya existe el numero de cedula',
            ErrorMessages::ERROR_LOGIN_AUTHENTICATE_EMPTY => 'Hubo un error, Llena los campos de cedula y clave',
            ErrorMessages::ERROR_LOGIN_AUTHENTICATE_DATA => 'Hubo un error, Cedula o clave incorrectos',
            ErrorMessages::ERROR_LOGIN_AUTHENTICATE => 'No se pudo procesar la solicitud. Ingrese la cedula y clave',
            ErrorMessages::ERROR_USER_UPDATEPHOTO => 'No se pudo procesar la solicitud. No se pudo guardar la foto.',
            ErrorMessages::ERROR_USER_UPDATEPASSWORD => 'No se pudo procesar la solicitud. No se pudo actualizar la clave.',
            ErrorMessages::ERROR_USER_UPDATEPASSWORD_THESAME => 'Error al cambiar la clave. La nueva clave es igual a la anterior.',
            ErrorMessages::ERROR_USER_UPDATEPHOTO_FORMAT => 'Hubo un error. Formato de la imagen no valido.',
            ErrorMessages::ERROR_USER_UPDATENAME => 'No se pudo procesar la solicitud. No se pudo actualizar el nombre.'
        ];

    }

    // devuelve el nombre del error que calce con el hash ingresado
    public function get($hash){
        return $this->errorList[$hash];
    }

    // toma una clave y va a devolver si existe o no
    public function existKey($key){//revisar nombre existKey para ver extra S despues de t
        if(array_key_exists($key, $this->errorList)){
            return true;
        }else{
            return false;
        }
    }
}

?>