<?php

class Admin extends SessionController {


    function __construct(){
        parent::__construct();
    
    } 


    function render(){

        $stats = $this->getStatistics();

        $this->view->render('admin/index', [
            'stats' => $stats

        ]);
    }
    //create peticionPago()
    function createCategory(){
        $this->view->render('admin/create-peticionpago');
    }

    //newCategory() ;aqui van cosas que puede hacer un admin
    function nuevaPeticionPago(){
        error_log('Admin::nuevaPeticionPago()');
        if($this->existPOST(['nombre', 'color'])){//TODO color se puede usar como contrato
            $name = $this->getPost('nombre');
            $color = $this->getPost('color');

            $peticionesPagoModel = new PeticionesPagoModel();

            if(!$PeticionesPagoModel->exists($name)){//TODO sino existe esa peticion de pago, crearla, trabajar en esto
                $PeticionesPagoModel->setName($name);
                $PeticionesPagoModel->setColor($color);
                $PeticionesPagoModel->save();
                error_log('Admin::newPeticionPago() => nueva peticion pago creada');
                $this->redirect('admin', ['success' => SuccessMessages::SUCCESS_ADMIN_NEWPETICIONPAGO]);
            }else{              //ruta    //mensajes  
                $this->redirect('admin', ['error' => ErrorMessages::ERROR_ADMIN_NEWPETICIONPAGO_EXISTS]);
            }
        }
    }


    //regresa la peticion de pago mas altra ingresada
    private function getMaxAmount($peticionesPago){
        $max = 0;
        foreach ($peticionesPago as $peticion) {
            $max = max($max, $peticion->getAmount());//funcion max
        }

        return $max;
    }


    //regresa la peticion de pago mas baja ingresada
    private function getMinAmount($peticionesPago){
        $min = $this->getMaxAmount($peticionesPago);
        foreach ($peticionesPago as $peticion) {
            $min = min($min, $peticion->getAmount());
        }

        return $min;
    }

    //saca el promedio del valor de las peticiones de pago
    private function getAverageAmount($peticionesPago){
        $sum = 0;
        foreach ($peticionesPago as $peticion) {
            $sum += $peticion->getAmount();
        }

        return ($sum / count($peticionesPago));
    }


    //TODO hacer aqui metodos para ver las peticiones autorizadas pero sin pagar
    private function getPeticionesPendientesPago($peticiones){
        $peticionesSinPagar = [];

        $indice = 0;
        foreach ($peticionesPago as $peticion) {

            if ($peticion->getAprobado && $peticion->getEstadoPago == 'pendiente' ) {//si estado aprobado y estado = pendiente
                if(!array_key_exists($peticion->getPeticionPagoId(), $peticionesSinPagar)){ //si el id no esta en el arreglo
                    $peticionesSinPagar[$indice] = $peticion;  //guarda la peticion pendiente de pago
                    $indice++;  
                }
                
            }

        }


        return $peticionesSinPagar;
    }



    //TODO hacer aqui metodo para ver las peticiones pendientes de autorizar.



    function getStatistics (){

    }
}




?>