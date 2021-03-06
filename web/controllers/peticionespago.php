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
            'contratistas' => $this->getAllContratistas()//peticiones_pago que han sido enviadas por los contratistas
                                                                               //TODO este metodo deberia de pasar la informacion como un array no objetos
            //'categories' => $this->getCategoryList()//BORRAR si no es necesario
        ]);
    }


     

        // crea una lista con los meses donde hay peticionesPago/planilla
        private function getDateList(){
        $months = [];
        $res = [];
        $joinModel = new JoinPeticionesUserModel();
        $peticionesJoin = $joinModel->getAllPeticionesNotOpen();//devuelve solo peticiones autorizado (pendientes) o pagados

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

    //Devuelve un array con todas las peticionesPago pendiente->autorizado->pagado
    function getAllPeticionesPagoRecibidas(){
        $joinModel = new JoinPeticionesUserModel();
        $peticionesJoin = $joinModel->getAllPeticionesNotOpen();//lista las peticiones de pago por id de user
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


        //manda a la vista las peticiones en estado pendiente, osea peticionespago pendientes de autorizar
        function getAllPeticionesPendientes(){
            $joinModel = new JoinPeticionesUserModel();
            $peticiones = $joinModel->getAllPeticionesPendientes();//lista las peticiones de pago por id de user
                                                                    //hacer un metodo para sacar las IDs con el resultado 
                                                                    //(array) de pagos enviado anteriormente en 
                                                                    //getAllPeticionesRecibidas
    
            

            if($peticiones){//SI tiene un resultado

                $res = [];

                foreach ($peticiones as $p) {
                    //guarda las peticionesPago en estructura json
                    array_push($res,  $p->toArray() ) ;//estamos metiendo un arreglo dentro de otro arreglo, simulando estructura json )
                    //array_push($res, $p->getPeticionPagoId());                                                   
                    
                }
                //$res = array_values(array_unique($res));//devuelve solo los valores del array. unique regresa solo valores unicos
                $this->sendToViewAsJSON($res); //res contine un arreglo con IDs de peticiones de pago


    
            }else{
                $res = [];

                array_push($res, [ 'id_planilla' => 'false', 'mensaje' => 'No hay planillas pendientes de autorizar.' ]);
                $this->sendToViewAsJSON($res);
            }


        }


    //devuelve solo los contratistas con nombres sin repetir extraidos de todas las peticiones
    // pendiente->autorizado->pagado
    function getAllContratistas(){
        
        $joinModel = new JoinPeticionesUserModel();
        $peticionesJoin = $joinModel->getAllPeticionesNotOpen();//lista las peticiones de pago por id de user
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
        $peticiones = $joinModel->getAllPeticionesNotOpen();//todas las peticiones menos las open, porque no han sido enviadas por los contratistas

        foreach ($peticiones as $p) {
            array_push($res, $p->toArray());//estamos metiendo un arreglo dentro de otro arreglo, simulando estructura json
        }
        header("HTTP/1.1 200 OK");
        header('Content-Type: application/json');
        echo json_encode($res);

    }


    //autoriza una peticionPago para ser pagada,
    //cambia el estado de la peticionPago/planilla de open a pendiente
    function autorizarPeticion($params){
        error_log("PETICIONESPAGO_CONTROLLER::autorizarPeticion()");
        
        if($params === NULL) $this->redirect('peticionespago', ['error' => ErrorMessages::ERROR_PETICIONPAGO_AUTORIZAR]);//TODO AGREGAR A LISTA
        $id = $params[0];
        //error_log("Pagos::delete() id = " . $id);
        // $planilla = new PeticionesPagoModel();
        // $planilla->get($id);
        // $planilla->setEstado("autorizado");



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
                $p->setEstadoPago("autorizado");
                $p->update();//CAMBIA ESTADO
            }

            //refresca la tabla de la vista
            $this->getAllPeticionesPendientes();//ENVIA solo peticiones pendientes de autorizar

        }else{
            $this->redirect('peticionespago', ['error' => ErrorMessages::ERROR_PETICIONPAGO_AUTORIZAR]);
        }
    }



    //rechaza una peticionPago/planilla,
    //cambia el estado de la peticionPago/planilla de autorizado a open
    function rechazarPeticion($params){
        error_log("PagosCONTROLLER::Pagar()");
        
        if($params === NULL) $this->redirect('peticionespago', ['error' => ErrorMessages::ERROR_PETICIONPAGO_RECHAZAR]);
        $id = $params[0];

        
        $this->model->get($id);
        $this->model->setEstado("open");

        $res = $this->model->update();//CAMBIAR ESTADO de la peticionPago autorizada



        

        if($res){//SI RES tiene un resultado
            $this->redirect('peticionespago', ['success' => SuccessMessages::SUCCESS_PETICIONPAGO_RECHAZAR]);//TODO AGREGAR A LISTA
            
            //refresca la tabla de la vista
            $this->getPeticionesPagoHistoryJSON();

        }else{
            $this->redirect('peticionespago', ['error' => ErrorMessages::ERROR_PETICIONPAGO_RECHAZAR]);
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
        
            $this->sendToViewAsJSON($array);
          

        }else{
            //no existe la peticion
            $res = [];
            array_push($res, [ 'id_planilla' => 'false', 'mensaje' => 'El numero de planilla no existe o esta mal escrita' ]);
            $this->sendToViewAsJSON($res);
        }
    }


    //recibe un array con una peticion
    //lo envia con formato JSON a la vista
    function sendToViewAsJSON($objectArray){
        error_log('PETICIONESPAGO_CONTROLLER::sendToViewAsJSON()');

        
        header("HTTP/1.1 200 OK");
        header('Content-Type: application/json');
        echo json_encode($objectArray);

    }




    //******************************************** */
    //TODO este codigo deberia de comunicarse con el controlador del dashboard para mandar la informacion que necesita 
    //la vista del dashboard. La vista del dashboard no deberia de pedir esta info directamente a este controlador
    //******************************************** */




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

        $peticionPago->setDescripcion($this->getPost('nombre_planilla'));
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

    
 


}



?>