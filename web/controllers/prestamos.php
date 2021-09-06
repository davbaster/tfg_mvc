<?php

require_once 'models/joinpagospeticionesmodel.php';
require_once 'models/peticionespagomodel.php';
require_once 'models/joinprestamosusermodel.php';
require_once 'models/pagosmodel.php';


class Prestamos extends SessionController{ 


    private $user;

    function __construct(){
        parent::__construct();

        $this->user = $this->getUserSessionData();
        error_log("Prestamos::constructor() ");
    }


    // //muestra la vista
    // function render(){
    //     error_log("Prestamos::RENDER() ");

    //     $this->view->render('pagos/index', [
    //         'user' => $this->user,
    //         'fechas' => $this->getDateList(),
    //         // 'peticionesPago' => $this->getPeticionesPagoList()//Nombres de los contratistas en las peticiones_pago
    //         //'peticionesPago' => $this->getPeticionesPagoIds()//IDs de los contratistas en las peticiones_pago
    //         'peticionesPagoAutorizadas' => $this->getPeticionesPagoIdsAutorizadas()//IDs de los contratistas en las peticiones_pago
    //                                     //hacer metodo getPeticionesPagoIdsAutorizadas() y utilizar en el modelo getAllPeticionesAutorizadas
    //                                     //"peticionesPagoIdAprobadas" => $joinModel->getAllPagosAutorizados(),
    //     ]);
    // }


    //recoger la informacion mandada de la vista para crear un nuevo pago
    //es llamado usando un ajax.
    function newPrestamo(){
        error_log('Prestamo::newPrestamo()');
        if(!$this->existPOST(['cedula', 'monto', 'peticion_pago_id', 'detalles'])){//si no existe el post con los parametros
            $this->redirect('dashboard', ['error' => ErrorMessages::ERROR_PRESTAMOS_NEWPRESTAMO_EMPTY]);
            return;
        }

        if($this->user == NULL){//valida session esta vacia
            $this->redirect('dashboard', ['error' => ErrorMessages::ERROR_PRESTAMOS_NEWPRESTAMO_EMPTY]);
            return;
        }

        $prestamo = new PrestamosModel();

        $prestamo->setEstado('pendiente');
        $prestamo->setCedula($this->getPost('cedula'));
        $prestamo->setMonto((float)$this->getPost('monto'));//float castea el valor a float
        //$pago->setFechaPago('');
        $prestamo->setPeticionPagoId($this->getPost('peticion_pago_id'));//setPeticionPagoId
        $prestamo->setRequestedBy($this->user->getCedula());//contratista que pidio el adelanto de salario
        $prestamo->setDetalles($this->getPost('detalles'));
        //$pago->setUserId($this->user->getId());

        $prestamo->save();
        $this->redirect('dashboard', ['success' => SuccessMessages::SUCCESS_PRESTAMOS_NEWPRESTAMO]);
    }


    //carga en un arreglo todos los IDs de las peticiones de pago que vienen del joinPagosPeticionesModel
    //TODO mejora: devolver una cantidad limitada
    function getPeticionesPagoIds(){
        $joinModel = new JoinPagosPeticionesModel();
        $peticiones = $joinModel->getAllPeticiones();//lista las peticiones de pago por id de user

        $res = [];
        foreach ($peticiones as $p) {
            //guarda IDs de peticiones de pago
            array_push($res, $p->getPeticionPagoId());//$p es un objeto del tipo joinPagosPeticionesModel
            //array_push($res, $pet->getCategoryId());
        }
        $res = array_values(array_unique($res));//devuelve solo los valores del array. unique regresa solo valores unicos
        return $res; //res contine un arreglo con IDs de peticiones de pago
    }

    //lista solo las id autorizadas (planillas aprobadas)
    //saca estas IDs de los pagos autorizados
    function getPeticionesPagoIdsAutorizadas(){
        $joinModel = new JoinPagosPeticionesModel();
        $pagosJoin = $joinModel->getAllPagosAutorizados();//lista las peticiones de pago por id de user
                                                                //hacer un metodo para sacar las IDs con el resultado 
                                                                //(array) de pagos enviado anteriormente en 
                                                                //$joinModel->getAllPagosAutorizados();
                                                                //getAllPagosAutorizados();

        $res = [];
        foreach ($pagosJoin as $p) {
            //guarda IDs de peticiones de pago
            array_push($res, $p->getPeticionPagoId());//$p es un objeto del tipo joinPagosPeticionesModel
            //array_push($res, $pet->getCategoryId());
        }
        $res = array_values(array_unique($res));//devuelve solo los valores del array. unique regresa solo valores unicos
        return $res; //res contine un arreglo con IDs de peticiones de pago
    }


    //devuelve un array con los datos de un prestamo dado un id de prestamo
    function buscar($params){
        error_log('PAGOSCONTROLLER::getPagosPlanilla()');

        if($params === NULL) $this->redirect('peticionespago', ['error' => ErrorMessages::ERROR_PAGOS_GETPAGOS]);//TODO AGREGAR A LISTA
        $idPrestamo = $params[0];
        
        $res = [];

        $model = new JoinPrestamosUserModel();
        $prestamo = $model->get($idPrestamo);//devuelve todos los pagos de una planilla

  
        if($prestamo){//SI  tiene un resultado
            //$this->redirect('pagos', ['success' => SuccessMessages::SUCCESS_PAGOS_PAGAR]);//TODO AGREGAR A LISTA
            
            
           
            array_push($res, $prestamo->toArray());//estamos metiendo un arreglo dentro de otro arreglo, simulando estructura json
        
            $this->sendJSON($res);
          

        }else{
            //no existe un prestamo
           
            array_push($res, [ 'cedula' => 'false', 'mensaje' => 'La planilla no tiene prestamos registrados.' ]);
            $this->sendJSON($res);
        }

    }


    // devuelve todos los elementos de un arreglo como si fuera un JSON para las llamadas AJAX
    //funciona como una API simple
    //TODOD acondicionar para prestamos
    function getPrestamos(){
        error_log('PRESTAMOS_CONTROLLER::getPrestamos()');
        
        $res = [];

        $model = new JoinPrestamosUserModel();
        $prestamos = $model->getAllPrestamosPendientes();//devuelve todos los pagos de una planilla

        if($prestamos){//SI  tiene un resultado   
            
            foreach ($prestamos as $p) {
                array_push($res, $p->toArray());//estamos metiendo un arreglo dentro de otro arreglo, simulando estructura json
            }
            $this->sendJSON($res);
          

        }else{
           
            array_push($res, [ 'cedula' => 'false', 'mensaje' => 'No hay prestamos pendientes.' ]);
            $this->sendJSON($res);
        }

    }



    //devuelve un array con prestamos aprobados
    function getPrestamosAutorizados(){
        error_log('PRESTAMOS_CONTROLLER::getPrestamos()');
        
        $res = [];

        $model = new JoinPrestamosUserModel();
        $prestamos = $model->getAllPrestamosAutorizados();//devuelve todos los pagos de una planilla

        if($prestamos){//SI  tiene un resultado   
            
            foreach ($prestamos as $p) {
                array_push($res, $p->toArray());//estamos metiendo un arreglo dentro de otro arreglo, simulando estructura json
            }
            $this->sendJSON($res);
          

        }else{
           
            array_push($res, [ 'cedula' => 'false', 'mensaje' => 'No hay prestamos aprobados.' ]);
            $this->sendJSON($res);
        }

    }



        //devuelve un array con prestamos Rechazados
        function getPrestamosRechazados(){
            error_log('PRESTAMOS_CONTROLLER::getPrestamosRechazados()');
            
            $res = [];
    
            $model = new JoinPrestamosUserModel();
            $prestamos = $model->getAllPrestamosRechazados();//devuelve todos los pagos de una planilla
    
            if($prestamos){//SI  tiene un resultado   
                
                foreach ($prestamos as $p) {
                    array_push($res, $p->toArray());//estamos metiendo un arreglo dentro de otro arreglo, simulando estructura json
                }
               
              
    
            }else{
               
                array_push($res, [ 'cedula' => 'empty', 'mensaje' => 'No hay prestamos rechazados.' ]);
                
            }

            return $res;//devuelve objeto en forma de array
    
        }


    //devuelve un array con los prestamos ligados a una peticionPagoId
    function getPrestamosPlanilla($params){
        error_log('PAGOSCONTROLLER::getPagosPlanilla()');

        if($params === NULL) $this->redirect('peticionespago', ['error' => ErrorMessages::ERROR_PAGOS_GETPAGOS]);//TODO AGREGAR A LISTA
        $id = $params[0];
        
        $res = [];

        $model = new PrestamosModel();
        $prestamos = $model->getAllPrestamosPorPeticion($id);//devuelve todos los pagos de una planilla

  
        if($prestamos){//SI  tiene un resultado
            //$this->redirect('pagos', ['success' => SuccessMessages::SUCCESS_PAGOS_PAGAR]);//TODO AGREGAR A LISTA
            
            
            foreach ($prestamos as $p) {
                array_push($res, $p->toArray());//estamos metiendo un arreglo dentro de otro arreglo, simulando estructura json
            }
            $this->sendJSON($res);
          

        }else{
            //$this->redirect('user', ['error' => ErrorMessages::ERROR_USER_BUSCAR_NOEXISTE]);
           
            array_push($res, [ 'cedula' => 'false', 'mensaje' => 'La planilla no tiene prestamos registrados.' ]);
            $this->sendJSON($res);
        }

    }



    //manda un prestamo en formato json a la vista
    //recibe un prestamo en formato array
    function sendJSON($array){
        error_log('PAGOS_CONTROLLER::sendJSON()');

        
        header("HTTP/1.1 200 OK");
        header('Content-Type: application/json');
        echo json_encode($array);

    }




    //devuelve un array con los datos de un prestamo dado un id de prestamo
    function autorizar($params){
        error_log('PRESTAMOS_CONTROLLER::autorizar()');

        if($params === NULL) $this->redirect('peticionespago', ['error' => ErrorMessages::ERROR_PAGOS_GETPAGOS]);//TODO AGREGAR A LISTA
        $idPrestamo = $params[0];
        
        $res = [];

        $model = new PrestamosModel();
        $prestamo = $model->get($idPrestamo);//devuelve todos los pagos de una planilla

    
        if($prestamo){//SI  tiene un resultado
            
            $prestamo->setEstado("autorizado");
            $prestamo->setApprover($this->user->getCedula());//obtiene la cedula del usuario actual
            $prestamo->setFechaAprobacion(date("Y/m/d"));
            
            if ($prestamo->update()) {# si se pudo actualizar

                $modelPrestamo = new JoinPrestamosUserModel();
                $prestamo = $modelPrestamo->get($idPrestamo);//devuelve un prestamo con la informacion de usuario

                array_push($res, $prestamo->toArray());//estamos metiendo un arreglo dentro de otro arreglo, simulando estructura json
        
                $this->sendJSON($res);
                
            }else {
                array_push($res, [ 'cedula' => 'false', 'mensaje' => 'Error aprobando el prestamo. Intente de nuevo.' ]);
                $this->sendJSON($res);
            }
            
            

        }else{
            //no existe un prestamo
            
            array_push($res, [ 'cedula' => 'false', 'mensaje' => 'La planilla no tiene prestamos registrados.' ]);
            $this->sendJSON($res);
        }

    }



    //devuelve un array con los datos de un prestamo dado un id de prestamo
    function rechazarPrestamo($params){
        error_log('PRESTAMOS_CONTROLLER::rechazar()');

        if($params === NULL) $this->redirect('peticionespago', ['error' => ErrorMessages::ERROR_PAGOS_GETPAGOS]);//TODO AGREGAR A LISTA
        $idPrestamo = $params[0];
        
        $res = [];

        $model = new PrestamosModel();
        $prestamo = $model->get($idPrestamo);//devuelve todos los pagos de una planilla

    
        if($prestamo){//SI  tiene un resultado
            
            $prestamo->setEstado("rechazado");
            //$prestamo->setApprover($this->user->getCedula());//obtiene la cedula del usuario actual
            //$prestamo->setFechaAprobacion(date("Y/m/d"));
            
            if ($prestamo->update()) {# si se pudo actualizar

                $modelPrestamo = new JoinPrestamosUserModel();
                $prestamo = $modelPrestamo->get($idPrestamo);//devuelve un prestamo con la informacion de usuario

                array_push($res, $prestamo->toArray());//estamos metiendo un arreglo dentro de otro arreglo, simulando estructura json
        
                $this->sendJSON($res);
                
            }else {
                array_push($res, [ 'cedula' => 'false', 'mensaje' => 'Error aprobando el prestamo. Intente de nuevo.' ]);
                $this->sendJSON($res);
            }
            
            

        }else{
            //no existe un prestamo
            
            array_push($res, [ 'cedula' => 'false', 'mensaje' => 'La planilla no tiene prestamos registrados.' ]);
            $this->sendJSON($res);
        }

    }



    //devuelve un array con los datos de un prestamo pagado dado un id de prestamo
    //cambia de estado el prestamo a pagado
    function pagar($params){
        error_log('PRESTAMOS_CONTROLLER::rechazar()');

        if($params === NULL) $this->redirect('peticionespago', ['error' => ErrorMessages::ERROR_PAGOS_GETPAGOS]);//TODO AGREGAR A LISTA
        $idPrestamo = $params[0];
        
        $res = [];

        $model = new PrestamosModel();
        $prestamo = $model->get($idPrestamo);//devuelve todos los pagos de una planilla

    
        if($prestamo){//SI  tiene un resultado
            
            $prestamo->setEstado("pagado");
            //$prestamo->setApprover($this->user->getCedula());//obtiene la cedula del usuario actual
            $prestamo->setFechaPago(date("Y/m/d"));
            
            if ($prestamo->update()) {# si se pudo actualizar

                $modelPrestamo = new JoinPrestamosUserModel();
                $prestamo = $modelPrestamo->get($idPrestamo);//devuelve un prestamo con la informacion de usuario

                array_push($res, $prestamo->toArray());//estamos metiendo un arreglo dentro de otro arreglo, simulando estructura json
        
                $this->sendJSON($res);
                
            }else {
                array_push($res, [ 'cedula' => 'false', 'mensaje' => 'Error pagando el prestamo. Intente de nuevo.' ]);
                $this->sendJSON($res);
            }
            
            

        }else{
            //no existe un prestamo
            
            array_push($res, [ 'cedula' => 'false', 'mensaje' => 'No hay prestamos con este ID.' ]);
            $this->sendJSON($res);
        }

    }


    //cambia el estado de open a pagado
    //rebaja al pago el prestamo
    function rebajarPrestamo($params){
        error_log("PagosCONTROLLER::Pagar()");
        
        if($params === NULL) $this->redirect('pagos', ['error' => ErrorMessages::ERROR_PRESTAMOS_PAGAR]);//TODO AGREGAR A LISTA
        $id = $params[0];
        //error_log("Pagos::delete() id = " . $id);
        // $pago = new PagosModel();
        // $pago->get($id);
        $this->model->get($id);
        $this->model->setEstadoPago("pagado");
        $res = $this->model->update();//CAMBIAR ESTADO

        if($res){//SI RES tiene un resultado
            //$this->redirect('pagos', ['success' => SuccessMessages::SUCCESS_PAGOS_PAGAR]);//TODO AGREGAR A LISTA
            $this->getPagosHistoryJSON();

        }else{
            $this->redirect('pagos', ['error' => ErrorMessages::ERROR_ADMIN_NEWPETICIONPAGO_EXISTS]);
        }
    }
  


}



?>