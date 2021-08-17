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
        $peticionesOpen         = $peticionesPagoModel->getPeticionesNoEnviadas();//peticiones que no se han enviado para aprobacion


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
            'petiRecientes'             => $petiRecientes,
            'peticionesOpen'             => $peticionesOpen
            
            
        ]);
    }

     // carga vista para nuevas peticion pago UI en DASHBOARD 
     function viewNewPagoDialog(){
        $peticionModel = new PeticionesPagoModel();
        $peticionesPago = $peticionModel->getPeticionesNoEnviadas(); //recibe las peticiones en estado OPEN, osea no mandadas a autorizar todavia 

        $user = new UserModel();
        $usuarios = $user->getAll();
                                                     

        //$this->view->render('peticionespago/agregarpago', [
        $this->view->render('dashboard/agregarpago', [

            'user'                      => $this->user,
            'peticionesPago'            => $peticionesPago,
            'usuarios'                  => $usuarios

            
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
            $descripcion['fecha'] = $p->getFechaCreacion();
            $descripcion['fechaPago'] = $p->getFechaPago();
            $descripcion['detalles'] = $p->getDetalles();

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
            $descripcion['fecha'] = $p->getFechaCreacion();
            $descripcion['fechaPago'] = $p->getFechaPago();
            $descripcion['detalles'] = $p->getDetalles();

            array_push($res, $descripcion); //va llenando un array $res con otro array $descripcion

            
        }
        return $res;
    }





}


?>