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


    //Lista peticiones autorizadas pero sin pagar
    private function getPeticionesPendientesPago($peticiones){
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


        return $peticionesSinPagar;
    }

    //Lista las pagos pendientes de pago
    private function getPagosPendientes($pagos){
        $pagosPendientes = [];

        $indice = 0;
        foreach ($pagos as $p) {

            if ($p->getEstadoPago == 'pendiente' ) {//estado = pendiente , un pago esta pendiente porque su peticion de pago fue autorizado previamente
                if(!array_key_exists($p->getId(), $pagosPendientes)){ //si el id no esta en el arreglo
                                                                    //FIXME porque verifico si el id esta almacenado en la lista, si lo que estoy guardadon 
                                                                    //es el objeto pago???
                    $pagosPendientes[$indice] = $p;  //guarda la peticion pendiente de pago
                    $indice++;  
                }
                
            }

        }


        return $pagosPendientes;
    }



    //TODO hacer aqui metodo para ver las peticiones pendientes de autorizar.


    //FIXME sacar todas las peticiones de pago para trabajar no es eficiente, arreglar.
    private function getStatistics(){
        $res = [];

        // $userModel = new UserModel();
        // $users = $userModel->getAll();
        
        $pagosModel = new PagosModel();
        $pagos = $pagosModel->getAll();

        $peticionesModel = new PeticionesPagoModel();
        $peticiones = $peticionesModel->getAll();

        $res['peticiones-pendientes'] = $this->getPeticionesPendientesPago($peticiones);
        $res['pagos-pendientes'] = $this->getPagosPendientes($pagos);
        

        // $res['count-users'] = count($users);
        // $res['count-expenses'] = count($expenses);
        // $res['max-expenses'] = $this->getMaxAmount($expenses);
        // $res['min-expenses'] = $this->getMinAmount($expenses);
        // $res['avg-expenses'] = $this->getAverageAmount($expenses);
        // $res['count-categories'] = count($categories);
        // $res['mostused-category'] = $this->getCategoryMostUsed($expenses);
        // $res['lessused-category'] = $this->getCategoryLessUsed($expenses);

        return $res;
    }


}




?>