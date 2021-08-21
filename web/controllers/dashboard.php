<?php

require_once 'models/pagosmodel.php';
require_once 'models/peticionespagomodel.php';
require_once 'models/joinpagospeticionesmodel.php'; 
require_once 'models/joinpeticionesusermodel.php';


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
     function viewNewPagoDialog($peticionId){
        //$peticionModel = new PeticionesUserModel();
        //$peticionesPago = $peticionModel->getAllPeticionesOpen($this->user->getCedula()); //recibe las peticiones en estado OPEN, osea no mandadas a autorizar todavia 
        $peticionesPago = $this->getPeticionPagoArray($peticionId); //recibe las peticiones en estado OPEN, osea no mandadas a autorizar todavia 
        $user = new UserModel();
        $usuarios = $user->getAll(); //todos los trabajadores que pueden trabajar en una planilla
                                                     

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






    //************PETICIONES******************* */
    //
    //***************************************** */

    //enviarAutorizarPeticion
        // carga vista para la creacion de una nueva peticion de pago/planilla
    //manda las peticiones de pago en estado Open, estas se les puede seguir metiendo pagos.
    //bien implementada, deberia de mandar solo arrays con la informacion formateada.//TODO
    function viewDialogCerrarPeticionPago($params){

        
        if($params[0] === NULL) $this->redirect('dashboard', ['error' => ErrorMessages::ERROR_PETICIONPAGO]);//TODO AGREGAR A LISTA

        $idPlanilla = $params[0];

        $peticionJoin = new JoinPeticionesUserModel();
        $this->view->render('dashboard/cerrarpeticionpago', [

           //"peticionesPagoAbiertasPorUsuario" => $peticionJoin->getAllPeticionesOpen($this->user->getCedula()), //este metodo trae todas las peticiones de pago como objetos, 

           //"peticionAbiertaSeleccionada" => $peticionJoin->getPeticionOpen($idPlanilla),//envia un objeto peticionuserJoinmodel 

           "peticionAbiertaSeleccionada"      => $this->getPeticionPagoArray($idPlanilla), //envia un objeto peticionuserJoinmodel en forma de array

         
                                                        //TODO deberia utilizar metodo que traiga las peticiones pendientes de pago y como un arreglo de datos
            "user"                            => $this->user
        ]);
    }



    function getPeticionPagoJSON($params){
        error_log('DASHBOARDCONTROLLER::getPeticionPagoJSON()');
        $res = [];

        if($params === NULL) $this->redirect('dashboard', ['error' => ErrorMessages::ERROR_PETICIONPAGO]);//TODO AGREGAR A LISTA

        $id = $params[0];

        $joinModel = new JoinPeticionesUserModel();
        $peticion = $joinModel->get($id);//devuelve la peticion dado un id

        
        array_push($res, $peticion->toArray());//estamos metiendo un arreglo dentro de otro arreglo, simulando estructura json
        
        header("HTTP/1.1 200 OK");
        header('Content-Type: application/json');
        echo json_encode($res);

    }

    //devuelve peticiones de pago en un array Imitando a un Json
    function getPeticionPagoArray($peticionId){
        error_log('DASHBOARDCONTROLLER::getPeticionPagoArray()');
        

        if($peticionId[0] === NULL) $this->redirect('dashboard', ['error' => ErrorMessages::ERROR_PETICIONPAGO]);//TODO AGREGAR A LISTA

        

        $joinModel = new JoinPeticionesUserModel();
        $peticion = $joinModel->getPeticionOpen($peticionId[0]);//devuelve la peticion dado un id

        
        //array_push($res, $peticion->toArray());//estamos metiendo un arreglo dentro de otro arreglo, simulando estructura json
        $res = $peticion->toArray();
        return $res;  //devuelve el objeto en forma de array

    }

    



    //***************************************** */
    //      FIN METODOS PARA PETICIONESPAGO
    //***************************************** */



}


?>