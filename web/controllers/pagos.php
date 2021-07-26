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
        $joinPagosPeticionesModel = new JoinPagosPeticionesModel();
        $joinPeticionesPago = $joinPagosPeticionesModel->getAll($this->user->getId());//lista las peticiones de pago por id de user

        $res = [];
        foreach ($joinPeticionesPago as $joinPet) {
            //guarda IDs de peticiones de pago
            array_push($res, $joinPet->getPeticionPagoId());//$joinPet es un objeto del tipo joinPagosPeticionesModel
            //array_push($res, $pet->getCategoryId());
        }
        $res = array_values(array_unique($res));
        return $res; //res contine un arreglo con IDs de peticiones de pago
    }


}



?>