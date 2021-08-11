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

            //Valores obligatorios
            $cedula = $this->getPost('cedula');
            $nombre = $this->getPost('nombre');
            $apellido1 = $this->getPost('apellido1');
            $apellido2 = $this->getPost('apellido2');
            $rol = $this->getPost('rol');

            //valores opcionales
            $telefono = $this->getPost('telefono');
            $direccion = $this->getPost('direccion');
            $cuentaBancaria = $this->getPost('cuentaBancaria');
            $email = $this->getPost('email');
            $contrasena = $this->getPost('contrasena');
            $confcontrasena = $this->getPost('confcontrasena');//confirmacion contrasena
            

            // validacion de los valores obligatorios recibidos
            if($cedula == '' || empty ($cedula) || $nombre == '' || empty($nombre) 
                || $apellido1 == '' || empty($apellido1) || $apellido2 == '' || empty($apellido2) 
                || $rol == '' || empty($rol) ){

                $this->redirect('signup', ['error' => ErrorMessages::ERROR_SIGNUP_NEWUSER_EMPTY]);
            }

            // hacer procedimiento para llenar info usuario?? sino, llenar las variables aqui.
            // se llena la info del usuario
            $user = new UserModel();
            $user->setCedula($cedula);
            $user->setNombre($nombre);
            $user->setApellido1($apellido1);
            $user->setApellido2($apellido2);
            $user->setRol($rol); 

            //llena variables opcionales
            $user->setTelefono($telefono);
            $user->setDireccion($direccion);
            $user->setCuentaBancaria($cuentaBancaria);
            $user->setEmail($email);
            if (!empty($contrasena)) {

                error_log('signup::Guardando contrasena: ' . $contrasena);
                $user->setContrasena($contrasena);
            }
            
            
            // error_log('informacion de $user: ' . $user->getRol());
            // $user = $this->obtenerInfoDeFormulario($user);
            
            

            if ($user->exists($cedula)) {
                // si ya existe la cedula
                $this->redirect('signup', ['error' => ErrorMessages::ERROR_SIGNUP_NEWUSER_EXISTS]);
            }else if ($user->save()){
                error_log('signup::newUser -> Cedula no existe, y se ha creado el usuario');
                // retorna al index, despliega mensaje de exito, despues de insertar a DB el usuario
                $this->redirect('', ['success' => SuccessMessages::SUCCESS_SIGNUP_NEWUSER]);
            }else{
                $this->redirect('signup', ['error' => ErrorMessages::ERROR_SIGNUP_NEWUSER]);
            }

        }else{
            // sino existe
            $this->redirect('signup', ['error' => ErrorMessages::ERROR_SIGNUP_NEWUSER]);
        }
    }

    // llena la info del form que viene en el post en un usuario nuevo
    // este metodo no esta sirviendo, parece que no llega el objeto.
    function obtenerInfoDeFormulario($user){

        // obtener la informacion del formulario enviado por el post
        $cedula = $this->getPost('cedula');
        $nombre = $this->getPost('nombre');
        $apellido1 = $this->getPost('apellido1');
        $apellido2 = $this->getPost('apellido2');
        $telefono = $this->getPost('telefono');
        $direccion = $this->getPost('direccion');
        $cuentaBancaria = $this->getPost('cuentaBancaria');
        $email = $this->getPost('email');
        $contrasena = $this->getPost('contrasena');


        // asignando los valores al objeto user
        $user->setCedula($cedula);
        $user->setNombre($nombre);
        $user->setApellido1($apellido1);
        $user->setApellido2($apellido2);
        $user->setTelefono($telefono);
        $user->setDireccion($direccion);
        $user->setCuentaBancaria($cuentaBancaria);
        $user->setEmail($email);
        $user->setContrasena($contrasena);
        $user->setRol($user);
        

        return $user;

    }

}

?>