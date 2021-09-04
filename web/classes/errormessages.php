<?php

class ErrorMessages{

    // ERROR_CONTROLLER_METHOD_ACTION
    
    const ERROR_USER_NEWUSER = "e001";
    const ERROR_USER_NEWUSER_EMPTY = "e002";
    const ERROR_USER_NEWUSER_EXISTS = "e003";
    const ERROR_LOGIN_AUTHENTICATE_EMPTY = "e004";
    const ERROR_LOGIN_AUTHENTICATE_DATA = "e005";
    const ERROR_LOGIN_AUTHENTICATE = "e006";
    const ERROR_USER_UPDATEPHOTO = "e007"; 
    const ERROR_USER_UPDATEPHOTO_FORMAT = "e008"; 
    const ERROR_USER_UPDATEPASSWORD = "e009";
    const ERROR_USER_UPDATEPASSWORD_THESAME = "e0010";
    const ERROR_USER_UPDATENAME = "e0011";
    const ERROR_ADMIN_NEWPETICIONPAGO_EXISTS = "e0012";
    const ERROR_PAGOS_NEWPAGO = "e0013";
    const ERROR_PAGOS_NEWPAGO_EMPTY = "e0014";
    const ERROR_PETICIONPAGOS_NEWPETICION = "e0015";
    const ERROR_PETICIONPAGOS_NEWPETICION_EMPTY = "e0016";
    const ERROR_PETICIONPAGO_AUTORIZAR = "e0017";
    const ERROR_PETICIONPAGO_ENVIAR = "e0018";
    const ERROR_USER_BUSCAR = "e0019";
    const ERROR_USER_BUSCAR_NOEXISTE = "e0020"; 
    const ERROR_PETICIONPAGO_BUSCAR = "e0021";
    const ERROR_PETICIONPAGO_RECHAZAR = "e0022";
    const ERROR_USER_UPDATEUSER = "e0023";
    const ERROR_USER_DISABLE  = "e0024";
    const ERROR_USER_UPDATEPASSWORD_EMPTY  = "e0025";
    const ERROR_PRESTAMOS_NEWPRESTAMO_EMPTY  = "e0026"; 
    const ERROR_PRESTAMOS_PAGAR  = "e0027"; 
    const ERROR_PAGOS_GETPAGOS  = "e0028";

    private $errorList = [];

    public function __construct(){

        $this->errorList = [
            
            ErrorMessages::ERROR_USER_NEWUSER => 'Hubo un error al intentar procesar la solicitud, el usuario ya existe',
            ErrorMessages::ERROR_USER_NEWUSER_EMPTY => 'Hubo un error al crear el usuario, hay campos vacios',
            ErrorMessages::ERROR_USER_NEWUSER_EXISTS => 'Hubo un error al intentar procesar la solicitud, ya existe el numero de cedula',
            ErrorMessages::ERROR_LOGIN_AUTHENTICATE_EMPTY => 'Hubo un error, Llena los campos de cedula y clave',
            ErrorMessages::ERROR_LOGIN_AUTHENTICATE_DATA => 'Hubo un error, Cedula o clave incorrectos',
            ErrorMessages::ERROR_LOGIN_AUTHENTICATE => 'No se pudo procesar la solicitud. Ingrese la cedula y clave',
            ErrorMessages::ERROR_USER_UPDATEPHOTO => 'No se pudo procesar la solicitud. No se pudo guardar la foto.',
            ErrorMessages::ERROR_USER_UPDATEPASSWORD => 'No se pudo procesar la solicitud. No se pudo actualizar la clave.',
            ErrorMessages::ERROR_USER_UPDATEPASSWORD_THESAME => 'Error al cambiar la clave. La nueva clave es igual a la anterior.',
            ErrorMessages::ERROR_USER_UPDATEPHOTO_FORMAT => 'Hubo un error. Formato de la imagen no valido.',
            ErrorMessages::ERROR_USER_UPDATENAME => 'No se pudo procesar la solicitud. No se pudo actualizar el nombre.',
            ErrorMessages::ERROR_ADMIN_NEWPETICIONPAGO_EXISTS => 'No se pudo crear la peticion de pago.',
            ErrorMessages::ERROR_PAGOS_NEWPAGO => 'No se pudo crear el pago. ',
            ErrorMessages::ERROR_PAGOS_NEWPAGO_EMPTY => 'No se pudo crear el pago. No hay informacion',
            ErrorMessages::ERROR_PETICIONPAGOS_NEWPETICION => 'No se pudo crear el la peticion de pago. ',
            ErrorMessages::ERROR_PETICIONPAGOS_NEWPETICION_EMPTY => 'No se pudo crear la peticion de pago. No hay informacion',
            ErrorMessages::ERROR_ADMIN_NEWPETICIONPAGO_EXISTS => 'No se pudo crear la peticion de pago. Ya existe una con ese nombre',
            ErrorMessages::ERROR_PETICIONPAGO_AUTORIZAR     => 'No se pudo autorizar la peticion de pago. Trate de nuevo',
            ErrorMessages::ERROR_PETICIONPAGO_ENVIAR     => 'No se pudo Enviar la peticion de pago. Trate de nuevo',
            ErrorMessages::ERROR_USER_BUSCAR     => 'Error al buscar. Trate de nuevo',
            ErrorMessages::ERROR_USER_BUSCAR_NOEXISTE     => 'Error al buscar. Usuario no existe en la base de datos.',
            ErrorMessages::ERROR_PETICIONPAGO_BUSCAR     => 'Error al buscar. Planilla no existe en la base de datos.',
            ErrorMessages::ERROR_PETICIONPAGO_RECHAZAR      => 'Error al rechazar Planilla. Hubo un problema.',
            ErrorMessages::ERROR_USER_UPDATEUSER            => 'Error al actualizar el usuario. Hubo un problema.',
            ErrorMessages::ERROR_USER_DISABLE            => 'Error al deshabilitar el usuario. No hay datos suficientes.',
            ErrorMessages::ERROR_USER_UPDATEPASSWORD_EMPTY   => 'Error al actualizar la clave. Faltan datos.',
            ErrorMessages::ERROR_PRESTAMOS_NEWPRESTAMO_EMPTY    => 'Error al crear el prestamo. Faltan datos.', 
            ErrorMessages::ERROR_PRESTAMOS_PAGAR                => 'Error al rebajar el prestamo.',
            ErrorMessages::ERROR_PAGOS_GETPAGOS     => 'No se pudo obtener los pagos. Hubo un error'
            
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