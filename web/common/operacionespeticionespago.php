<?php

require_once 'models/peticionespagosmodel.php';




class OperacionesPeticionesPagos {

    public function __construct(){
        

    }


    //Lista peticiones pendientes de aprobar
    private function getPeticionesPendientesAprobacion($peticiones){
        $peticionesSinPagar = [];

        $indice = 0;
        foreach ($peticiones as $peticion) {

            if ($peticion->getAprobado && $peticion->getEstadoPago == 'pendiente' ) {//si estado aprobado y estado = pendiente
                if(!array_key_exists($peticion->getPeticionPagoId(), $peticionesSinPagar)){ //si el id no esta en el arreglo
                                                                    //FIXME porque verifico si el id esta almacenado en la lista, si lo que estoy guardando 
                                                                    //es el objeto peticionPago???
                    $peticionesSinPagar[$indice] = $peticion;  //guarda la peticion pendiente de pago
                    $indice++;  
                }
                
            }

        }

    }

}

?>