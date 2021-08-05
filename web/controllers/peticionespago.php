<?php

require_once 'models/joinpagospeticionesmodel.php';
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
        error_log("PeticionesPagos::RENDER() ");

        $this->view->render('peticionespago/index', [
            'user' => $this->user,
            'dates' => $this->getDateList(),//FIXME se le tiene que mandar dates para algo?
            'peticionesPago' => $this->getPeticionesPagoList()//peticiones_pago//TODO hay que revisar si esta utilizando el metodo adecuado,
                                                                               //TODO este metodo deberia de pasar la informacion como un array no objetos
            //'categories' => $this->getCategoryList()//BORRAR si no es necesario
        ]);
    }


     // carga vista para nuevas peticion pago UI
     function viewPago(){
        $peticionModel = new PeticionesPagoModel();

        $peticionesPago = $peticionModel->getAll(); //este metodo trae todas las peticiones de pago como objetos, 
                                                     

        $this->view->render('peticionespago/agregarpago', [

            'user'                      => $this->user,
            'peticionesPago'           => $peticionesPago

            
        ]);
    }


    // carga vista para la creacion de una nueva peticion de pago/planilla
    //manda las peticiones de pago en estado Open, estas se les puede seguir metiendo pagos.
    //bien implementada, deberia de mandar solo arrays con la informacion formateada.//TODO
    function viewPeticion(){
        $peticionModel = new PeticionesPagoModel();
        $this->view->render('peticionespago/agregarpeticionpago', [
            "peticionespago" => $peticionModel->getAll(), //este metodo trae todas las peticiones de pago como objetos, 
                                                        //TODO deberia utilizar metodo que traiga las peticiones pendientes de pago y como un arreglo de datos
            "user" => $this->user
        ]);
    }

    //FIXME arreglar metodo, los campos del arreglo de existPOST son incorrectos
    function newPago(){
        error_log('Pagos::newPago()');
        if(!$this->existPOST(['title', 'amount', 'peticion_pago', 'date'])){//si no existe el post con los parametros
            $this->redirect('dashboard', ['error' => Errors::ERROR_PETICIONPAGOS_NEWPETICION_EMPTY]);//TODO agregar error al errormessages.php
            return;
        }

        if($this->user == NULL){//valida session no esta vacia
            $this->redirect('dashboard', ['error' => Errors::ERROR_PETICIONPAGOS_NEWPETICION]);//TODO agregar error al errormessages.php
            return;
        }

        $pago = new PagosModel();

        $pago->setTitle($this->getPost('title'));
        $pago->setAmount((float)$this->getPost('amount'));//float castea el valor a float
        //$pago->setCategoryId($this->getPost('category'));
        $pago->setPeticionPagoId($this->getPost('peticion_pago'));//setPeticionPagoId
        $pago->setDate($this->getPost('date'));
        $pago->setUserId($this->user->getId());

        $pago->save();
        $this->redirect('dashboard', ['success' => Success::SUCCESS_PETICIONPAGOS_NEWPETICION]);//TODO agregar error al errormessages.php
    }

    //FIXME arreglar metodo, los campos del arreglo de existPOST son incorrectos
    function newPeticionPago(){
        error_log('Pagos::newPago()');
        if(!$this->existPOST(['title', 'amount', 'peticion_pago', 'date'])){//si no existe el post con los parametros
            $this->redirect('dashboard', ['error' => Errors::ERROR_PETICIONPAGOS_NEWPETICION_EMPTY]);//TODO agregar error al errormessages.php
            return;
        }

        if($this->user == NULL){//valida session no esta vacia
            $this->redirect('dashboard', ['error' => Errors::ERROR_PETICIONPAGOS_NEWPETICION]);//TODO agregar error al errormessages.php
            return;
        }

        $pago = new PagosModel();

        $pago->setTitle($this->getPost('title'));
        $pago->setAmount((float)$this->getPost('amount'));//float castea el valor a float
        //$pago->setCategoryId($this->getPost('category'));
        $pago->setPeticionPagoId($this->getPost('peticion_pago'));//setPeticionPagoId
        $pago->setDate($this->getPost('date'));
        $pago->setUserId($this->user->getId());

        $pago->save();
        $this->redirect('dashboard', ['success' => Success::SUCCESS_PETICIONPAGOS_NEWPETICION]);//TODO agregar error al errormessages.php
    }


    
    //TODO borrar que?
    function delete($params){
        error_log("Pagos::delete()");
        
        if($params === NULL) $this->redirect('pagos', ['error' => Errors::ERROR_ADMIN_NEWPETICIONPAGO_EXISTS]);//TODO agregar error al errormessages.php
        $id = $params[0];
        error_log("Pagos::delete() id = " . $id);
        $res = $this->model->delete($id);

        if($res){//SI RES tiene un resultado
            $this->redirect('pagos', ['success' => Success::SUCCESS_PETICIONPAGOS_DELETE]);//TODO agregar error al errormessages.php
        }else{
            $this->redirect('pagos', ['error' => Errors::ERROR_ADMIN_NEWPETICIONPAGO_EXISTS]);//TODO agregar error al errormessages.php
        }
    }


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

     //TODO revisar si hace lo que tiene que hacer
     // crea una lista con los meses donde hay peticiones de pago (Planillas)
    //  private function getDateList(){
    //     $months = [];
    //     $res = [];
    //     $joinModel = new JoinPagosPeticionesModel();
    //     $peticiones = $joinModel->getAll($this->user->getId());

    //     foreach ($peticiones as $p) {
    //         array_push($months, substr($p->getDate(),0, 7 ));//suprime desde 0 y termina en 7
    //     }
    //     $months = array_values(array_unique($months));//devuelve solo los valores del array. unique regresa solo valores unicos
    //     //mostrar los últimos 3 meses
    //     if(count($months) >3){
    //         array_push($res, array_pop($months));//quita un elemento de arreglo x3
    //         array_push($res, array_pop($months));
    //         array_push($res, array_pop($months));
    //     }
    //     return $res;
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