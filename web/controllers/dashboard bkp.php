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

        $peticionEstadistica       = $this->pagosOpenEstadistica($peticionesOpen[0]->getId());
        

        $this->view->render('dashboard/index', [
            'user'                      => $this->user,
            'pagosPendientes'           => $pagosPendientes,
            'petiPendientesPagar'       => $petiPendientesPagar,
            'petiPendientesAprobar'     => $petiPendientesAprobar,
            'pagosRecientes'            => $pagosRecientes,
            'petiRecientes'             => $petiRecientes,
            'peticionesOpen'             => $peticionesOpen,
            'peticionEstadistica'        =>$peticionEstadistica   
            
            
        ]);
    }

     // carga vista para nuevas peticion pago UI en DASHBOARD 
     function viewNewPagoDialog($peticionId){
        //$peticionModel = new PeticionesUserModel();
        //$peticionesPago = $peticionModel->getAllPeticionesOpen($this->user->getCedula()); //recibe las peticiones en estado OPEN, osea no mandadas a autorizar todavia 
        $peticionesPago = $this->getPeticionPagoArray($peticionId[0]); //recibe las peticiones en estado OPEN, osea no mandadas a autorizar todavia 
        $user = new UserModel();
        $usuarios = $user->getAll(); //todos los trabajadores que pueden trabajar en una planilla
                                                     

        //$this->view->render('peticionespago/agregarpago', [
        $this->view->render('dashboard/agregarpago', [

            'user'                      => $this->user,
            'peticionesPago'            => $peticionesPago,
            'usuarios'                  => $usuarios

            
        ]);
    }


    //obtiene la lista de expenses y $n tiene el n??mero de expenses por transacci??n
     private function getPagos($n = 0){
        if($n < 0) return NULL;
        error_log("Dashboard::getPagos() id = " . $this->user->getId());
        $pagos = new PagosModel();
        return $pagos->getByUserIdAndLimit($this->user->getId(), $n);   
    }


    //obtine los pagos con estado de pendiente ,
    //getPetiPagosPorAprobar
    function getPagosPorCancelar(){
        $res = [];
        $joinPagosPeticionesModel = new JoinPagosPeticionesModel();
        $pagosPendientes          = $joinPagosPeticionesModel->getAllPagosPendientes();

        
        foreach ($pagosPendientes as $p) {
            $descripcion = [];
 
            $descripcion['cedula'] = $p->getPagoId();
            $descripcion['nombre'] = $p->getNombre();
            $descripcion['apellido1'] = $p->getApellido1();
            $descripcion['apellido2'] = $p->getApellido2();
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
            $descripcion['apellido1'] = $p->getApellido1();
            $descripcion['apellido2'] = $p->getApellido2();
            $descripcion['monto'] = $p->getAmount();
            $descripcion['planilla'] = $p->getPeticionPagoId();
            $descripcion['fecha'] = $p->getFechaCreacion();
            $descripcion['fechaPago'] = $p->getFechaPago();
            $descripcion['detalles'] = $p->getDetalles();

            array_push($res, $descripcion); //va llenando un array $res con otro array $descripcion

            
        }
        return $res;
    }

    //devuelve un array con los pagos Open dado un peticionPagoId
    function pagosOpenPerIdPeticion($peticionPagoId){
        error_log('PAGOSCONTROLLER::pagosOpenPerIdPeticion()');
        
        $res = [];

        $joinModel = new JoinPagosPeticionesModel();
        $pagosJoin = $joinModel->getAllPagosOpen($peticionPagoId);//cambie de getAllPagos a getAllPagosAutorizados

        foreach ($pagosJoin as $p) {
            array_push($res, $p->toArray());//estamos metiendo un arreglo dentro de otro arreglo, simulando estructura json
        }

        return $res;

    }



    //devuelve un array con los pagos Open dado un peticionPagoId
    function pagosOpenEstadistica($peticionPagoId){
        error_log('PAGOSCONTROLLER::pagosOpenEstadistica()');

        if($peticionPagoId === NULL) $this->redirect('dashboard', ['error' => ErrorMessages::ERROR_PETICIONPAGO]);//TODO AGREGAR A LISTA
        
        $res = [];
        $montoTotal = 0;
        $cantPagos = 0;

        $joinModel = new JoinPagosPeticionesModel();
        $pagosOpen = $joinModel->getAllPagosOpen($peticionPagoId);//cambie de getAllPagos a getAllPagosAutorizados

        foreach ($pagosOpen as $p) {

            $montoTotal+= $p->getAmount();
            $cantPagos++;
        }

        $array = ["montoTotal" => $montoTotal, "cantPagos" => $cantPagos ];

        array_push($res, $array );
       

        return $res;

    }






    //************PETICIONES******************* */
    //
    //***************************************** */

    //VistaenviarAutorizarPeticion
        // carga vista para la creacion de una nueva peticion de pago/planilla
    //manda las peticiones de pago en estado Open, estas se les puede seguir metiendo pagos.
    //bien implementada, deberia de mandar solo arrays con la informacion formateada.//TODO
    function viewDialogCerrarPeticionPago($params){

        
        if($params[0] === NULL) $this->redirect('dashboard', ['error' => ErrorMessages::ERROR_PETICIONPAGO]);//TODO AGREGAR A LISTA

        $peticionPagoId = $params[0];

        //$pagosJoin = new JoinPagosPeticionesModel();
        $this->view->render('dashboard/cerrarpeticionpago', [

           //"peticionesPagoAbiertasPorUsuario" => $peticionJoin->getAllPeticionesOpen($this->user->getCedula()), //este metodo trae todas las peticiones de pago como objetos, 

           //"peticionAbiertaSeleccionada" => $peticionJoin->getPeticionOpen($idPlanilla),//envia un objeto peticionuserJoinmodel 

           "peticionAbiertaSeleccionada"      => $this->getPeticionPagoArray($peticionPagoId), //envia un objeto peticionuserJoinmodel en forma de array

            "pagosOpenPerId"                  => $this->pagosOpenPerIdPeticion($peticionPagoId),
         
                                                        //TODO deberia utilizar metodo que traiga las peticiones pendientes de pago y como un arreglo de datos
            "user"                            => $this->user
        ]);
    }

    //cierra la planilla, la accion la ejecuta el usuario contratista o un administrador
    //cambia el estado de una planilla de OPEN a PENDIENTE de autorizacion
    //deberia refrescar la pagina para que refleje las planillas pendientes de autorizacion por parte del administrador.
    function enviarPeticionPago(){
        error_log("DASHBOARD_CONTROLLER::enviarPeticionPago()");


        if ($this->existPOST(['peticionPago_id'])) {
            $peticionPagoId = $this->getPost('peticionPago_id');
                
                // validacion de los valores obligatorios recibidos
                // if($peticionPagoId == '' || empty ($peticionPagoId) ){

                //     // redirige a pagina de inicio
                //     $this->redirect('', ['error' => ErrorMessages::ERROR_LOGIN_AUTHENTICATE_EMPTY]);
                // }
            
            if($peticionPagoId === NULL) $this->redirect('dashboard', ['error' => ErrorMessages::ERROR_PETICIONPAGO_AUTORIZAR]);
            
            //error_log("Pagos::delete() id = " . $id);
            $peticionPagoModel = new PeticionesPagoModel();
            // $pago->get($id);
            $peticionPagoModel->get($peticionPagoId);
            $peticionPagoModel->setEstado("pendiente");//cambia estado planilla a pendiente de aprobaci[on]
            $res = $peticionPagoModel->update();//CAMBIAR ESTADO

            if($res){//SI RES tiene un resultado
                $this->redirect('dashboard', ['success' => SuccessMessages::SUCCESS_PETICIONPAGO_ENVIAR]);
               

            }else{
                $this->redirect('dashboard', ['error' => ErrorMessages::ERROR_PETICIONPAGO_ENVIAR]);
            }
        }else{
    
            $this->redirect('dashboard', ['error' => ErrorMessages::ERROR_PETICIONPAGO_ENVIAR]);
        }
    }


    //Recibe un array con datos en formato json
    //envia los datos codificados como json a la vista
    function sendInfoJSON($arrayJSON){
        error_log('DASHBOARDCONTROLLER::sendInfoJSON()');


        
        header("HTTP/1.1 200 OK");
        header('Content-Type: application/json');
        echo json_encode($arrayJSON);

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
        

        if($peticionId === NULL) $this->redirect('dashboard', ['error' => ErrorMessages::ERROR_PETICIONPAGO]);//TODO AGREGAR A LISTA

        

        $joinModel = new JoinPeticionesUserModel();
        $peticion = $joinModel->getPeticionOpen($peticionId);//devuelve la peticion dado un id

        
        //array_push($res, $peticion->toArray());//estamos metiendo un arreglo dentro de otro arreglo, simulando estructura json
        $res = $peticion->toArray();
        return $res;  //devuelve el objeto en forma de array

    }

    



    //***************************************** */
    //      FIN METODOS PARA PETICIONESPAGO
    //***************************************** */



}


?>