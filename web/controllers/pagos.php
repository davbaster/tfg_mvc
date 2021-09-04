<?php

require_once 'models/joinpagospeticionesmodel.php';
require_once 'models/peticionespagomodel.php';
require_once 'models/pagosmodel.php';


class Pagos extends SessionController{ 


    private $user;

    function __construct(){
        parent::__construct();

        $this->user = $this->getUserSessionData();
        error_log("Pagos::constructor() ");
    }


    //muestra la vista
    function render(){
        error_log("Pagos::RENDER() ");

        $this->view->render('pagos/index', [
            'user' => $this->user,
            'fechas' => $this->getDateList(),
            // 'peticionesPago' => $this->getPeticionesPagoList()//Nombres de los contratistas en las peticiones_pago
            //'peticionesPago' => $this->getPeticionesPagoIds()//IDs de los contratistas en las peticiones_pago
            'peticionesPagoAutorizadas' => $this->getPeticionesPagoIdsAutorizadas()//IDs de los contratistas en las peticiones_pago
                                        //hacer metodo getPeticionesPagoIdsAutorizadas() y utilizar en el modelo getAllPeticionesAutorizadas
                                        //"peticionesPagoIdAprobadas" => $joinModel->getAllPagosAutorizados(),
        ]);
    }


    //recoger la informacion mandada de la vista para crear un nuevo pago
    //es llamado usando un ajax.
    function newPago(){
        error_log('Pagos::newPago()');
        if(!$this->existPOST(['cedula', 'amount', 'peticion_pago_id', 'detalles'])){//si no existe el post con los parametros
            $this->redirect('dashboard', ['error' => ErrorMessages::ERROR_PAGOS_NEWPAGO_EMPTY]);
            return;
        }

        if($this->user == NULL){//valida session no esta vacia
            $this->redirect('dashboard', ['error' => ErrorMessages::ERROR_PAGOS_NEWPAGO]);
            return;
        }

        $pago = new PagosModel();

        $pago->setEstadoPago('open');
        $pago->setCedula($this->getPost('cedula'));
        $pago->setAmount((float)$this->getPost('amount'));//float castea el valor a float
        $pago->setFechaPago('');
        $pago->setPeticionPagoId($this->getPost('peticion_pago_id'));//setPeticionPagoId
        $pago->setDetalles($this->getPost('detalles'));
        //$pago->setUserId($this->user->getId());

        $pago->save();
        $this->redirect('dashboard', ['success' => SuccessMessages::SUCCESS_PAGOS_NEWPAGO]);
    }


     // carga vista para nuevas peticion pago UI
     function create(){
        //$peticionPago = new PeticionesPagoModel();
        $joinModel = new JoinPagosPeticionesModel(); //join entre pagos y peticiones

        $this->view->render('pagos/create', [
            "peticionPago" => $peticionPago->getAll(), //sirve para crear un pago basado en las planillas
            //"peticionesPagoIdAprobadas" => $joinModel->getAllPagosAutorizados(),
            "user" => $this->user
        ]);
    } 

    

    //
    //devuelve un array con los pagos Open dado un peticionPagoId
    function getPagosPlanilla($params){
        error_log('PAGOSCONTROLLER::getPagosPlanilla()');

        if($params === NULL) $this->redirect('peticionespago', ['error' => ErrorMessages::ERROR_PAGOS_GETPAGOS]);//TODO AGREGAR A LISTA
        $id = $params[0];
        
        $res = [];

        $joinModel = new JoinPagosPeticionesModel();
        $pagosJoin = $joinModel->getAllPagosPorPeticion($id);//devuelve todos los pagos de una planilla

  
        if($pagosJoin){//SI  tiene un resultado
            //$this->redirect('pagos', ['success' => SuccessMessages::SUCCESS_PAGOS_PAGAR]);//TODO AGREGAR A LISTA
            
            
            foreach ($pagosJoin as $p) {
                array_push($res, $p->toArray());//estamos metiendo un arreglo dentro de otro arreglo, simulando estructura json
            }
            $this->sendJSON($res);
          

        }else{
            //$this->redirect('user', ['error' => ErrorMessages::ERROR_USER_BUSCAR_NOEXISTE]);
           
            array_push($res, [ 'cedula' => 'false', 'mensaje' => 'La planilla no tiene pagos registrados.' ]);
            $this->sendJSON($res);
        }

    }



    //manda un pago en formato json a la vista
    //recibe un pago en formato array
    function sendJSON($array){
        error_log('PAGOS_CONTROLLER::sendJSON()');

        
        header("HTTP/1.1 200 OK");
        header('Content-Type: application/json');
        echo json_encode($array);

    }




     // crea una lista con los meses donde hay pagos
     private function getDateList(){
        $months = [];
        $res = [];
        $joinModel = new JoinPagosPeticionesModel();
        $pagos = $joinModel->getAllPagosAutorizados();//devuelve solo pagos autorizados pendientes de pago o pagados

        foreach ($pagos as $p) {
            //TODO En futuro se podria sacar el mes y el a;o por aparte para hacer el filtro mas minucioso
            array_push($months, substr($p->getFechaCreacion(),0, 7 ));//suprime desde 0 y termina en 7
        }
        $months = array_values(array_unique($months));//devuelve solo los valores del array. unique regresa solo valores unicos

        foreach ($months as $month) {
            array_push($res, $month);
        }

        return $res;
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




    // devuelve todos los elementos de un arreglo como si fuera un JSON para las llamadas AJAX
    //funciona como una API simple
    function getPagosHistoryJSON(){
        error_log('PAGOSCONTROLLER::getPagosHistoryJSON()');
        
        $res = [];

        $joinModel = new JoinPagosPeticionesModel();
        $peticiones = $joinModel->getAllPagosAutorizados();//cambie de getAllPagos a getAllPagosAutorizados

        foreach ($peticiones as $p) {
            array_push($res, $p->toArray());//estamos metiendo un arreglo dentro de otro arreglo, simulando estructura json
        }
        header("HTTP/1.1 200 OK");
        header('Content-Type: application/json');
        echo json_encode($res);

    }




    //cambia el estado de open a pagado
    function pagar($params){
        error_log("PagosCONTROLLER::Pagar()");
        
        if($params === NULL) $this->redirect('pagos', ['error' => ErrorMessages::ERROR_ADMIN_PAGOS_PAGAR]);//TODO AGREGAR A LISTA
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
    
    // //getExpensesJSON
    // function getPagosJSON(){

    //     header('Content-Type: application/json');

    //     $res = [];
    //     $peticionesPagoIds     = $this->getPeticionesPagoIds();//esta en esta misma clase, linea 67
    //     $peticionPagoNames  = $this->getPeticionPagoList(); //linea 105
    //     $categoryColors = $this->getCategoryColorList();

    //     //acomodando informacion para google chart
    //     array_unshift($peticionPagoNames, 'mes');
    //     array_unshift($categoryColors, 'categorias');
    //     /* array_unshift($categoryNames, 'categorias');
    //     array_unshift($categoryColors, NULL); */

    //     $months = $this->getDateList();

    //     //itera entre los ids y los meses para acomodar los pagos
    //     //crea matriz
    //     for($i = 0; $i < count($months); $i++){
    //         $item = array($months[$i]);
    //         for($j = 0; $j < count($peticionesPagoIds); $j++){
    //             $total = $this->getTotalByMonthAndCategory( $months[$i], $peticionesPagoIds[$j]);
    //             array_push( $item, $total );
    //         }   
    //         array_push($res, $item);
    //     }
        
    //     array_unshift($res, $peticionPagoNames);
    //     array_unshift($res, $categoryColors);
        
    //     echo json_encode($res);
    // }


    //getTotalByMonthAndCategory
    //devuelve el total pagado segun el id de una peticion de pago
    function getTotalByMonthAndCategory($date, $peticionPagoId){
        $iduser = $this->user->getId();
        $pagos = new PagosModel();

        $total = $pagos->getTotalByMonthAndCategory($date, $peticionPagoId, $iduser);
        if($total == NULL) $total = 0;

        return $total;
    }


    //
    function delete($params){
        error_log("Pagos::delete()");
        
        if($params === NULL) $this->redirect('pagos', ['error' => ErrorMessages::ERROR_ADMIN_NEWPETICIONPAGO_EXISTS]);
        $id = $params[0];
        error_log("Pagos::delete() id = " . $id);
        $res = $this->model->delete($id);

        if($res){//SI RES tiene un resultado
            $this->redirect('pagos', ['success' => SuccessMessages::SUCCESS_PAGOS_DELETE]);
        }else{
            $this->redirect('pagos', ['error' => ErrorMessages::ERROR_ADMIN_NEWPETICIONPAGO_EXISTS]);
        }
    }

    //TODO hacer funcion pagar
    //cambia el estado del pago a pagado

}



?>