<?php

require_once 'models/loginmodel.php';

class Login extends SessionController{ 

        

    function __construct(){
        parent::__construct();
        
        error_log('Login::construct -> inicio de Login');
        
    }

    function render(){
        error_log('Login::render -> Carga el index de login');
        $this->view->render('login/index');
    }

    // valida si existe user y password
    //si existe valida si tiene datos
    function authenticate(){
        if ($this->existPOST(['cedula', 'contrasena'])) {
            $cedula = $this->getPost('cedula');
            $contrasena = $this->getPost('contrasena');

            // error_log('Login::authenticate ->POST cedula: ' . $cedula . ' clave: ' . $contrasena);

            // validacion de los valores obligatorios recibidos
            if($cedula == '' || empty ($cedula) || $contrasena == '' || empty($contrasena)  ){

                // redirige a pagina de inicio
                $this->redirect('', ['error' => ErrorMessages::ERROR_LOGIN_AUTHENTICATE_EMPTY]);
            }

            // regresa usuario de tipo userModel
            //$this->model->holaMundo() ; //Debugging:
            $user = $this->model->login($cedula, $contrasena); //ERROR no esta llamando a login, algo pasa con ese model
            
            //error_log('Login::authenticate -> Revisando datos del usuario devuelto. Cedula: ' . $user->getCedula() ); //Debugging: revisando la cedula que trae el Objeto
            
            //var_dump($user);//Debugging:  Revisando si el usuario viene con datos

            if ( $user != NULL ) {

                if ( $user->getEstado() == "inactivo" ) {

                    error_log('Login::authenticate -> Usuario Inactivo: ' . $user->getCedula() .', rol: ' . $user->getRol());
                    $this->redirect('', ['error' => ErrorMessages::ERROR_USER_DISABLED]);

                }else {
                    // si se autentico el usuario
                    //inicializa, esta dentro de la clase session controller
                    error_log('Login::authenticate -> Cedula: ' . $user->getCedula() .', rol: ' . $user->getRol());
                    $this->initialize($user);
                }


            }else{
                // sino se pudo autenticar
                // redirige a pagina de inicio
                //error_log('Login::authenticate -> Usuario es NULL');
                $this->redirect('', ['error' => ErrorMessages::ERROR_LOGIN_AUTHENTICATE_DATA]);

            }

        }else{
            // no se pudo autenticar
            $this->redirect('', ['error' => ErrorMessages::ERROR_LOGIN_AUTHENTICATE]);
        }
    }
}

?>