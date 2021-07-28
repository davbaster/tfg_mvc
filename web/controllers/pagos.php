<?php


class Expenses extends SessionController{ 


    private $user;

    function __construct(){
        parent::__construct();

        $this->user = $this->getUserSessionData();
        error_log("Pagos::constructor() ");
    }


    //muestra la vista
    function render(){
        error_log("Gastos::RENDER() ");

        $this->view->render('pagos/index', [
            'user' => $this->user,
            'dates' => $this->getDateList(),
            'peticionesPago' => $this->getPeticionesPagoList()//peticiones_pago
            //'categories' => $this->getCategoryList()//BORRAR si no es necesario
        ]);
    }


    //
    function newPago(){
        error_log('Pagos::newPago()');
        if(!$this->existPOST(['title', 'amount', 'peticion_pago', 'date'])){//si no existe el post con los parametros
            $this->redirect('dashboard', ['error' => Errors::ERROR_PAGOS_NEWPAGO_EMPTY]);
            return;
        }

        if($this->user == NULL){//valida session no esta vacia
            $this->redirect('dashboard', ['error' => Errors::ERROR_PAGOS_NEWPAGO]);
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
        $this->redirect('dashboard', ['success' => Success::SUCCESS_PAGOS_NEWPAGO]);
    }


     // carga vista para nuevas peticion pago UI
     function create(){
        $peticionPago = new PeticionModel();
        $this->view->render('pagos/create', [
            "peticionPago" => $peticionPago->getAll(),
            "user" => $this->user
        ]);
    } 

    //
    function getPeticionesPagoIds(){
        $joinModel = new JoinPagosPeticionesModel();
        $peticiones = $joinModel->getAll($this->user->getId());//lista las peticiones de pago por id de user

        $res = [];
        foreach ($peticiones as $p) {
            //guarda IDs de peticiones de pago
            array_push($res, $p->getPeticionPagoId());//$p es un objeto del tipo joinPagosPeticionesModel
            //array_push($res, $pet->getCategoryId());
        }
        $res = array_values(array_unique($res));//devuelve solo los valores del array. unique regresa solo valores unicos
        return $res; //res contine un arreglo con IDs de peticiones de pago
    }


     // crea una lista con los meses donde hay pagos
     private function getDateList(){
        $months = [];
        $res = [];
        $joinModel = new JoinPagosPeticionesModel();
        $peticiones = $joinModel->getAll($this->user->getId());

        foreach ($peticiones as $p) {
            array_push($months, substr($p->getDate(),0, 7 ));//suprime desde 0 y termina en 7
        }
        $months = array_values(array_unique($months));//devuelve solo los valores del array. unique regresa solo valores unicos
        //mostrar los últimos 3 meses
        if(count($months) >3){
            array_push($res, array_pop($months));//quita un elemento de arreglo x3
            array_push($res, array_pop($months));
            array_push($res, array_pop($months));
        }
        return $res;
    }


    // crea una lista con las peticiones de Pago donde hay pagos
    // getCategoryList()
    private function getPeticionPagoList(){
        $res = [];
        $joinModel = new JoinPagosPeticionesModel();
        $peticiones = $joinModel->getAll($this->user->getId());

        foreach ($peticiones as $p) {
            array_push($res, $p->getNamePeticionPago());
        }
        $res = array_values(array_unique($res));

        return $res;
    }


    // crea una lista con los colores dependiendo de las categorias
    //color esta en tabla peticiones_pago
    //color podria se contrato, contratista
    private function getCategoryColorList(){
        $res = [];
        $joinModel = new JoinPagosPeticionesModel();
        $peticiones = $joinModel->getAll($this->user->getId());

        foreach ($peticiones as $p) {
            array_push($res, $p->getColor());
        }
        $res = array_unique($res);
        $res = array_values(array_unique($res));

        return $res;
    }


    // devuelve todos los elementos de un arreglo como si fuera un JSON para las llamadas AJAX
    //funciona como una API simple
    function getHistoryJSON(){
        header('Content-Type: application/json');
        $res = [];
        $joinModel = new JoinPagosPeticionesModel();
        $peticiones = $joinModel->getAll($this->user->getId());

        foreach ($peticiones as $p) {
            array_push($res, $p->toArray());//estamos metiendo un arreglo dentro de otro arreglo, simulando estructura json
        }
        
        echo json_encode($res);

    }
    
    //getExpensesJSON
    function getPagosJSON(){
        header('Content-Type: application/json');

        $res = [];
        $peticionesPagoIds     = $this->getPeticionesPagoIds();//esta en esta misma clase, linea 67
        $peticionPagoNames  = $this->getPeticionPagoList(); //linea 105
        $categoryColors = $this->getCategoryColorList();

        //acomodando informacion para google chart
        array_unshift($peticionPagoNames, 'mes');
        array_unshift($categoryColors, 'categorias');
        /* array_unshift($categoryNames, 'categorias');
        array_unshift($categoryColors, NULL); */

        $months = $this->getDateList();

        //itera entre los ids y los meses para acomodar los pagos
        //crea matriz
        for($i = 0; $i < count($months); $i++){
            $item = array($months[$i]);
            for($j = 0; $j < count($peticionesPagoIds); $j++){
                $total = $this->getTotalByMonthAndCategory( $months[$i], $peticionesPagoIds[$j]);
                array_push( $item, $total );
            }   
            array_push($res, $item);
        }
        
        array_unshift($res, $peticionPagoNames);
        array_unshift($res, $categoryColors);
        
        echo json_encode($res);
    }


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
        
        if($params === NULL) $this->redirect('pagos', ['error' => Errors::ERROR_ADMIN_NEWPETICIONPAGO_EXISTS]);
        $id = $params[0];
        error_log("Pagos::delete() id = " . $id);
        $res = $this->model->delete($id);

        if($res){//SI RES tiene un resultado
            $this->redirect('pagos', ['success' => Success::SUCCESS_PAGOS_DELETE]);
        }else{
            $this->redirect('pagos', ['error' => Errors::ERROR_ADMIN_NEWPETICIONPAGO_EXISTS]);
        }
    }

}



?>