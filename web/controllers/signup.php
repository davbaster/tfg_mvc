<?php

require_once 'models/usermodel.php';

class Signup extends SessionController{

    function __construct()
    {
        parent::__construct();
    }

    // despliega la pagina
    function render(){
        $this->view->render('login/signup', []);

    }

    // 
    function newUser(){

        error_log('signup::newUser -> ingresa al metodo newUser');


        // verifica si existe cedula y password
        if($this->existPost(['cedula', 'contrasena'])){


            $cedula = $this->getPost('cedula');
            $contrasena = $this->getPost('contrasena');

            // validacion de los valores recibidos
            if($cedula == '' || empty ($cedula) || $contrasena == '' || empty($contrasena)){
                $this->redirect('signup', ['error' => ErrorMessages::ERROR_SIGNUP_NEWUSER_EMPTY]);
            }

            $user = new UserModel();
            $user->setCedula($cedula);
            $user->setContrasena($contrasena);
            $user->setRole($user);

            if ($user->exists($cedula)) {
                // si ya existe la cedula
                $this->redirect('signup', ['error' => ErrorMessages::ERROR_SIGNUP_NEWUSER_EXISTS]);
            }else if ($user->save()){
                // retorna al index, despliega mensaje de exito, despues de insertar a DB el usuario
                $this->redirect('', ['success' => ErrorMessages::ERROR_SIGNUP_NEWUSER_EXISTS]);
            }else{
                $this->redirect('signup', ['error' => ErrorMessages::ERROR_SIGNUP_NEWUSER]);
            }

        }else{
            // sino existe
            $this->redirect('signup', ['error' => ErrorMessages::ERROR_SIGNUP_NEWUSER]);
        }
    }

}

?>