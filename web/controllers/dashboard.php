<?php

require_once 'models/pagosmodel.php';
require_once 'models/peticionespagomodel.php';
require_once 'models/joinpagospeticionesmodel.php';

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


        //$pagosModel             = new PagosModel();
        //$pagosPendientes        = $pagosModel->getPagosPendientes();

        $peticionesPagoModel    = new PeticionesPagoModel();
        $petiPendientesAprobar  = $peticionesPagoModel->getPeticionesPendientesAprobacion();//planillas (peticiones pago) pendientes de aprobar
        $petiPendientesPagar    = $peticionesPagoModel->getPeticionesPendientesPago();//planillas (peticiones pago) aprobadas y pendientes de pago

        //$joinPagosPeticionesModel = new JoinPagosPeticionesModel();
        //$pagosPendientes          = $joinPagosPeticionesModel->getAllPagosPendientes();
        $pagosPendientes            = $this->getPagosPorCancelar();

        $pagosRecientes             = $this->getPagosRecientes();
        

        $this->view->render('dashboard/index', [
            'user'                      => $this->user,
            'pagosPendientes'           => $pagosPendientes,
            'petiPendientesPagar'       => $petiPendientesPagar,
            'petiPendientesAprobar'     => $petiPendientesAprobar,
            'pagosRecientes'            => $pagosRecientes,
            'petiRecientes'             => $petiRecientes
            
        ]);
    }


    //obtiene la lista de expenses y $n tiene el número de expenses por transacción
     private function getPagos($n = 0){
        if($n < 0) return NULL;
        error_log("Dashboard::getPagos() id = " . $this->user->getId());
        $pagos = new PagosModel();
        return $pagos->getByUserIdAndLimit($this->user->getId(), $n);   
    }


    //obtine los pagos con estado de pending ,
    //getPetiPagosPorAprobar
    function getPagosPorCancelar(){
        $res = [];
        $joinPagosPeticionesModel = new JoinPagosPeticionesModel();
        $pagosPendientes          = $joinPagosPeticionesModel->getAllPagosPendientes();

        
        foreach ($pagosPendientes as $p) {
            $descripcion = [];
 
            $descripcion['cedula'] = $p->getPagoId();
            $descripcion['nombre'] = $p->getNombre();
            $descripcion['apellido1'] = $p->getApellido();
            $descripcion['monto'] = $p->getAmount();
            $descripcion['planilla'] = $p->getPeticionPagoId();
            $descripcion['fecha'] = $p->getDate();

            array_push($res, $descripcion); //va llenando un array $res con otro array $descripcion

            
        }
        return $res;
    }



    //pagos recientes
    function getPagosRecientes(){
        $res = [];
        $joinPagosPeticionesModel = new JoinPagosPeticionesModel();
        $pagosRecientes          = $joinPagosPeticionesModel->getPagosRecientes();

        
        foreach ($pagosRecientes as $p) {
            $descripcion = [];
 
            $descripcion['cedula'] = $p->getPagoId();
            $descripcion['nombre'] = $p->getNombre();
            $descripcion['apellido1'] = $p->getApellido();
            $descripcion['monto'] = $p->getAmount();
            $descripcion['planilla'] = $p->getPeticionPagoId();
            $descripcion['fecha'] = $p->getDate();

            array_push($res, $descripcion); //va llenando un array $res con otro array $descripcion

            
        }
        return $res;
    }





}


?>