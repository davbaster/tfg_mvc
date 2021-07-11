<?php
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
            $user = $this->model->login($cedula, $contrasena);

            if ( $user != NULL ) {
                // si se autentico el usuario
                //inicializa, esta dentro de la clase session controller
                $this->initialize($user);
            }else{
                // sino se pudo autenticar
                // redirige a pagina de inicio
                $this->redirect('', ['error' => ErrorMessages::ERROR_LOGIN_AUTHENTICATE_DATA]);

            }

        }else{
            // no se pudo autenticar
            $this->redirect('', ['error' => ErrorMessages::ERROR_LOGIN_AUTHENTICATE]);
        }
    }
}

?>