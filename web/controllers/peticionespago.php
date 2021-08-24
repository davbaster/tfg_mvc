<?php

require_once 'models/joinpeticionesusermodel.php';
require_once 'models/peticionespagomodel.php';
require_once 'models/pagosmodel.php';


class PeticionesPago extends SessionController{ 


    private $user;

    function __construct(){
        parent::__construct();

        $this->user = $this->getUserSessionData();
        error_log("PeticionesPagos::constructor() ");
    }


    //muestra la vista
    function render(){
        error_log("PeticionesPagosCONTROLLER::RENDER() ");
        

        $this->view->render('peticionespago/index', [
            'user' => $this->user,
            'fechas' => $this->getDateList(),//FIXME se le tiene que mandar dates para algo?
            'peticionesPagoRecibidas' => $this->getAllContratistas()//peticiones_pago que han sido enviadas por los contratistas
                                                                               //TODO este metodo deberia de pasar la informacion como un array no objetos
            //'categories' => $this->getCategoryList()//BORRAR si no es necesario
        ]);
    }


     

        // crea una lista con los meses donde hay peticionesPago/planilla
        private function getDateList(){
        $months = [];
        $res = [];
        $joinModel = new JoinPeticionesUserModel();
        $peticionesJoin = $joinModel->getAllPeticionesAutorizadas();//devuelve solo peticiones autorizados pendientes de pago o pagados

        foreach ($peticionesJoin as $p) {
            //TODO En futuro se podria sacar el mes y el a;o por aparte para hacer el filtro mas minucioso
            array_push($months, substr($p->getFechaCreacion(),0, 7 ));//suprime desde 0 y termina en 7
        }
        $months = array_values(array_unique($months));//devuelve solo los valores del array. unique regresa solo valores unicos

        foreach ($months as $month) {
            array_push($res, $month);
        }

        return $res;
    }

    //Devuelve un array con todas las peticionesPago pendiente->autorizada->pagada
    function getAllPeticionesPagoRecibidas(){
        $joinModel = new JoinPeticionesUserModel();
        $peticionesJoin = $joinModel->getAllPeticiones();//lista las peticiones de pago por id de user
                                                                //hacer un metodo para sacar las IDs con el resultado 
                                                                //(array) de pagos enviado anteriormente en 
                                                                //getAllPeticionesRecibidas

        $res = [];
        foreach ($peticionesJoin as $p) {
            //guarda las peticionesPago en estructura json
            array_push($res,  $p->toArray() ) ;//estamos metiendo un arreglo dentro de otro arreglo, simulando estructura json )
            //array_push($res, $p->getPeticionPagoId());                                                   
            
        }
        //$res = array_values(array_unique($res));//devuelve solo los valores del array. unique regresa solo valores unicos
        return $res; //res contine un arreglo con IDs de peticiones de pago
    }


    //devuelve solo los contratistas con nombres sin repetir extraidos de todas las peticiones
    // pendiente->autorizada->pagada
    function getAllContratistas(){
        $joinModel = new JoinPeticionesUserModel();
        $peticionesJoin = $joinModel->getAllPeticiones();//lista las peticiones de pago por id de user
                                                                //hacer un metodo para sacar las IDs con el resultado 
                                                                //(array) de pagos enviado anteriormente en 
                                                                //getAllPeticionesRecibidas

        $res = [];
        foreach ($peticionesJoin as $p) {
            //guarda las peticionesPago en estructura json
            //array_push($res,  $p->toArray() ) ;//estamos metiendo un arreglo dentro de otro arreglo, simulando estructura json )
            array_push($res, $p->getNombre(). ' '. $p->getApellido1().' '.$p->getApellido2()  );                                                   
            
        }
        $res = array_values(array_unique($res));//devuelve solo los valores del array. unique regresa solo valores unicos
        return $res; //res contine un arreglo con IDs de peticiones de pago
    }


    function getPeticionesPagoHistoryJSON(){
        error_log('PAGOSCONTROLLER::getPagosHistoryJSON()');
        
        $res = [];

        $joinModel = new JoinPeticionesUserModel();
        $peticiones = $joinModel->getAllPeticiones();//todas las peticiones menos las open, porque no han sido enviadas por los contratistas

        foreach ($peticiones as $p) {
            array_push($res, $p->toArray());//estamos metiendo un arreglo dentro de otro arreglo, simulando estructura json
        }
        header("HTTP/1.1 200 OK");
        header('Content-Type: application/json');
        echo json_encode($res);

    }


    //autorizarPago
    function autorizarPeticion($params){
        error_log("PagosCONTROLLER::Pagar()");
        
        if($params === NULL) $this->redirect('peticionespago', ['error' => ErrorMessages::ERROR_PETICIONPAGO_AUTORIZAR]);//TODO AGREGAR A LISTA
        $id = $params[0];
        //error_log("Pagos::delete() id = " . $id);
        // $pago = new PagosModel();
        // $pago->get($id);
        $this->model->get($id);
        $this->model->setEstado("autorizado");

        $res = $this->model->update();//CAMBIAR ESTADO de la peticionPago autorizada



        

        if($res){//SI RES tiene un resultado
            //$this->redirect('peticionespago', ['success' => SuccessMessages::SUCCESS_PETICIONPAGO_AUTORIZAR]);//TODO AGREGAR A LISTA
            

            //aqui va metodo que cambia el estado de todos los pagos que tengan la peticionPagoID autorizada
            //METODO
            $pagosModel = new PagosModel();
            $pagos = $pagosModel->getAllByPeticionPagoId($id);

            foreach ($pagos as $p) {
                //cambia el estado a pendiente de pago
                                                
                //$p->get($id);
                $p->setEstadoPago("pendiente");
                $p->update();//CAMBIA ESTADO
            }

            //refresca la tabla de la vista
            $this->getPeticionesPagoHistoryJSON();

        }else{
            $this->redirect('peticionespago', ['error' => ErrorMessages::ERROR_PETICIONPAGO_AUTORIZAR]);
        }
    }


    //BUSCAR PETICION Usuario
    //Busca un usuario usando la cedula
    function buscar($params){
        error_log("PETICIONESPAGO_CONTROLLER::Buscar()");
        
        if($params === NULL) $this->redirect('peticionespago', ['error' => ErrorMessages::ERROR_PETICIONPAGO_BUSCAR]);//
        $id = $params[0];
        $joinPeticion = new JoinPeticionesUserModel();
        $res = $joinPeticion->get($id);


        if($res){//SI RES tiene un resultado
            //$this->redirect('pagos', ['success' => SuccessMessages::SUCCESS_PAGOS_PAGAR]);//TODO AGREGAR A LISTA
            $array = [];
            array_push($array, $res->toArray());//estamos metiendo un arreglo dentro de otro arreglo, simulando estructura json
        
            $this->getPeticionJSON($array);
          

        }else{
            //no existe la peticion
            $res = [];
            array_push($res, [ 'id_planilla' => 'false', 'mensaje' => 'El numero de planilla no existe o esta mal escrita' ]);
            $this->getPeticionJSON($res);
        }
    }


    //recibe un array con una peticion
    //lo envia con formato JSON a la vista
    function getPeticionJSON($objectArray){
        error_log('PETICIONESPAGO_CONTROLLER::getPeticionJSON()');

        
        header("HTTP/1.1 200 OK");
        header('Content-Type: application/json');
        echo json_encode($objectArray);

    }




    //******************************************** */
    //TODO este codigo deberia de comunicarse con el controlador del dashboard para mandar la informacion que necesita 
    //la vista del dashboard. La vista del dashboard no deberia de pedir esta info directamente a este controlador
    //******************************************** */




    // carga vista para la creacion de una nueva peticion de pago/planilla
    //manda las peticiones de pago en estado Open, estas se les puede seguir metiendo pagos.
    //bien implementada, deberia de mandar solo arrays con la informacion formateada.//TODO
    function viewPeticion(){
        $peticionModel = new PeticionesPagoModel();
        $this->view->render('peticionespago/agregarpeticionpago', [
           // "peticionespago" => $peticionModel->getAll(), //este metodo trae todas las peticiones de pago como objetos, 
                                                        //TODO deberia utilizar metodo que traiga las peticiones pendientes de pago y como un arreglo de datos
            "user" => $this->user
        ]);
    }


    //Recibe los datos de la vista agregarpeticionpago.php y los guarda en un objeto peticion pago
    //guarda la informacion de la planialla
    function newPeticionPago(){
        error_log('PeticionesPago::newPeticionPago()');
        if(!$this->existPOST(['nombre_planilla','cedula', 'amount', 'detalles'])){//si no existe el post con los parametros
            $this->redirect('dashboard', ['error' => ErrorMessages::ERROR_PETICIONPAGOS_NEWPETICION_EMPTY]);
            return;
        }

        if($this->user == NULL){//valida session no esta vacia
            $this->redirect('dashboard', ['error' => ErrorMessages::ERROR_PETICIONPAGOS_NEWPETICION]);
            return;
        }

        $peticionPago = new PeticionesPagoModel();

        $peticionPago->setNombre($this->getPost('nombre_planilla'));
        $peticionPago->setMonto((float)$this->getPost('amount'));//float castea el valor a float
        $peticionPago->setCedula($this->getPost('cedula'));//setPeticionPagoId
        $peticionPago->setEstado("open"); //open =Peticion de pago recientemete abierta
        $peticionPago->setDetalles($this->getPost('detalles'));

        $peticionPago->save();
        $this->redirect('dashboard', ['success' => SuccessMessages::SUCCESS_PETICIONPAGOS_NEWPETICION]);
    }


    //**************************************************************************** */
    //Fin de metodos que conversar con otro controlador
    //**************************************************************************** */

    
    //TODO borrar que?
    // function delete($params){
    //     error_log("Pagos::delete()");
        
    //     if($params === NULL) $this->redirect('pagos', ['error' => ErrorMessages::ERROR_ADMIN_NEWPETICIONPAGO_EXISTS]);
    //     $id = $params[0];
    //     error_log("Pagos::delete() id = " . $id);
    //     $res = $this->model->delete($id);

    //     if($res){//SI RES tiene un resultado
    //         $this->redirect('pagos', ['success' => SuccessMessages::SUCCESS_PETICIONPAGOS_DELETE]);
    //     }else{
    //         $this->redirect('pagos', ['error' => ErrorMessages::ERROR_ADMIN_NEWPETICIONPAGO_EXISTS]);
    //     }
    // }


        // //FIXME arreglar metodo, los campos del arreglo de existPOST son incorrectos
    // function newPago(){
    //     error_log('Pagos::newPago()');
    //     if(!$this->existPOST(['title', 'amount', 'peticion_pago', 'date'])){//si no existe el post con los parametros
    //         $this->redirect('dashboard', ['error' => ErrorMessages::ERROR_PETICIONPAGOS_NEWPETICION_EMPTY]);//TODO agregar error al errormessages.php
    //         return;
    //     }

    //     if($this->user == NULL){//valida session no esta vacia
    //         $this->redirect('dashboard', ['error' => ErrorMessages::ERROR_PETICIONPAGOS_NEWPETICION]);//TODO agregar error al errormessages.php
    //         return;
    //     }

    //     $pago = new PagosModel();

    //     $pago->setTitle($this->getPost('title'));
    //     $pago->setAmount((float)$this->getPost('amount'));//float castea el valor a float
    //     //$pago->setCategoryId($this->getPost('category'));
    //     $pago->setPeticionPagoId($this->getPost('peticion_pago'));//setPeticionPagoId
    //     $pago->setDate($this->getPost('date'));
    //     $pago->setUserId($this->user->getId());

    //     $pago->save();
    //     $this->redirect('dashboard', ['success' => SuccessMessages::SUCCESS_PETICIONPAGOS_NEWPETICION]);//TODO agregar error al errormessages.php
    // }


    //TODO revisar si metodo funciona, o hay otro metodo que ya hace lo que tiene que hacer este.
    // function getPeticionesPagoIds(){
    //     $joinModel = new JoinPagosPeticionesModel();
    //     $peticiones = $joinModel->getAll($this->user->getId());//lista las peticiones de pago por id de user

    //     $res = [];
    //     foreach ($peticiones as $p) {
    //         //guarda IDs de peticiones de pago
    //         array_push($res, $p->getPeticionPagoId());//$p es un objeto del tipo joinPagosPeticionesModel
    //         //array_push($res, $pet->getCategoryId());
    //     }
    //     $res = array_values(array_unique($res));//devuelve solo los valores del array. unique regresa solo valores unicos
    //     return $res; //res contine un arreglo con IDs de peticiones de pago
    // }




    //TODO revisar si este metodo debe de existir y si hay otro que hace la misma funcionalidad
    // crea una lista con las peticiones de Pago donde hay pagos
    // getCategoryList()
    // private function getPeticionesPagoList(){
    //     $res = [];
    //     $joinModel = new JoinPagosPeticionesModel();
    //     $peticiones = $joinModel->getAll($this->user->getId());

    //     foreach ($peticiones as $p) {
    //         array_push($res, $p->getNamePeticionPago());
    //     }
    //     $res = array_values(array_unique($res));

    //     return $res;
    // }




    // devuelve todos los elementos de un arreglo como si fuera un JSON para las llamadas AJAX
    //funciona como una API simple
    // function getHistoryJSON(){
    //     header('Content-Type: application/json');
    //     $res = [];
    //     $joinModel = new JoinPagosPeticionesModel();
    //     $peticiones = $joinModel->getAll($this->user->getId());

    //     foreach ($peticiones as $p) {
    //         array_push($res, $p->toArray());//estamos metiendo un arreglo dentro de otro arreglo, simulando estructura json
    //     }
        
    //     echo json_encode($res);

    // }
    
    // //getExpensesJSON
    // function getPeticionesPagoJSON(){

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


    //TODO revisar si se necesita este metodo o si solo para pagos
    //getTotalByMonthAndCategory
    //devuelve el total pagado segun el id de una peticion de pago
    // function getTotalByMonthAndCategory($date, $peticionPagoId){
    //     $iduser = $this->user->getId();
    //     $pagos = new PagosModel();

    //     $total = $pagos->getTotalByMonthAndCategory($date, $peticionPagoId, $iduser);
    //     if($total == NULL) $total = 0;

    //     return $total;
    // }




}



?>