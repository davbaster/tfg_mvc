<?php

class Dashboard extends SessionController{

    private $user;

    function __construct(){
        parent::__construct();

        $this->user = $this->getUserSessionData();
        error_log("Dashboard::constructor() ");
    }


    //
    function render(){
        error_log("Dashboard::RENDER() ");
        $pagosModel             = new PagosModel();
        $pagos                  = $this->getPagos(5);
        $totalThisMonth         = $pagosModel->getTotalAmountThisMonth($this->user->getId());
        $maxExpensesThisMonth   = $pagosModel->getMaxPaymentThisMonth($this->user->getId());
        $peticionesPagos        = $this->getPeticionesPagos();

        $this->view->render('dashboard/index', [
            'user'                 => $this->user,
            'pagos'                => $pagos,
            'totalAmountThisMonth' => $totalThisMonth,
            'maxPagosThisMonth'    => $maxPagosThisMonth,
            'peticionesPagos'      => $peticionesPagos
        ]);
    }


    //obtiene la lista de expenses y $n tiene el número de expenses por transacción
     private function getPagos($n = 0){
        if($n < 0) return NULL;
        error_log("Dashboard::getPagos() id = " . $this->user->getId());
        $pagos = new PagosModel();
        return $pagos->getByUserIdAndLimit($this->user->getId(), $n);   
    }


    //getCategories
    function getPeticionesPagos(){
        $res = [];
        $peticionesPagoModel = new PeticionesPagoModel();
        $pagosModel = new PagosModel();

        $peticiones = $peticionesPagoModel->getAll();

        foreach ($peticiones as $p) {
            $peticionesArray = [];
            //obtenemos la suma de amount de expenses por categoria
            $total = $pagosModel->getTotalByCategoryThisMonth($p->getId(), $this->user->getId());
            // obtenemos el número de expenses por categoria por mes
            $numberOfPagos = $pagosModel->getNumberOfExpensesByCategoryThisMonth($p->getId(), $this->user->getId());
            
            if($numberOfPagos > 0){
                $peticionesArray['total'] = $total;
                $peticionesArray['count'] = $numberOfPagos;
                $peticionesArray['peticion'] = $p;
                array_push($res, $peticionesArray);
            }
            
        }
        return $res;
    }

    //getExpenses
    // public function getPagos(){



    // }

    //getPlanillas
    public function getCategories(){

    }


}


?>