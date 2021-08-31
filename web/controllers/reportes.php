<?php

//require_once 'models/usermodel.php'; //TODO borrar si no se ocupa

class Reportes extends SessionController{

    private $user;

    function __construct(){
        parent::__construct();

        $this->user = $this->getUserSessionData();
        error_log("user " . $this->user->getNombre());
    }


    function render(){
        $this->view->render('reportes/index', [//pasando info a la vista
            "user" => $this->user
        ]);
    }



        //BUSCAR USUARIO
    //Busca un usuario usando la cedula
    function buscar($params){
        error_log("USER_CONTROLLER::Buscar()");
        
        if($params === NULL) $this->redirect('user', ['error' => ErrorMessages::ERROR_USER_BUSCAR]);//
        $id = $params[0];
        $user = new UserModel();
        $res = $user->get($id);


        if($res){//SI RES tiene un resultado
            //$this->redirect('pagos', ['success' => SuccessMessages::SUCCESS_PAGOS_PAGAR]);//TODO AGREGAR A LISTA
            $res = [];
            array_push($res, $user->toArray());//estamos metiendo un arreglo dentro de otro arreglo, simulando estructura json
        
            $this->getUserJSON($res);
          

        }else{
            //$this->redirect('user', ['error' => ErrorMessages::ERROR_USER_BUSCAR_NOEXISTE]);
            $res = [];
            array_push($res, [ 'cedula' => 'false', 'mensaje' => 'El n&uacute;mero de c&eacute;dula provisto no tuvo resultados' ]);
            $this->getUserJSON($res);
        }
    }


            //BUSCAR USUARIO
    //Busca un usuario usando la cedula
    function buscarPagos($params){
        error_log("USER_CONTROLLER::Buscar()");
        
        if($params === NULL) $this->redirect('user', ['error' => ErrorMessages::ERROR_USER_BUSCAR]);//
        $id = $params[0];
        $user = new UserModel();
        $res = $user->get($id);


        error_log('PAGOSCONTROLLER::getPagosHistoryJSON()');
        
        $res = [];

        $joinModel = new JoinPagosPeticionesModel();
        $peticiones = $joinModel->getAllPagosHechos($id);//devuelve todos los pagos pagados dado un id.

        foreach ($peticiones as $p) {
            array_push($res, $p->toArray());//estamos metiendo un arreglo dentro de otro arreglo, simulando estructura json
        }
        header("HTTP/1.1 200 OK");
        header('Content-Type: application/json');
        echo json_encode($res);



    }
    
 

    //manda un usuario en formato array a la vista
    //recibe un usuario en formato array
    function getUserJSON($userArray){
        error_log('USER_CONTROLLER::getUserJSON()');

        
        header("HTTP/1.1 200 OK");
        header('Content-Type: application/json');
        echo json_encode($userArray);

    }












}



?>