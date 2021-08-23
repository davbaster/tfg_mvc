<?php

//require_once 'models/usermodel.php'; //TODO borrar si no se ocupa

class User extends SessionController{

    private $user;

    function __construct(){
        parent::__construct();

        $this->user = $this->getUserSessionData();
        error_log("user " . $this->user->getName());
    }


    function render(){
        $this->view->render('user/index', [//pasando info a la vista
            "user" => $this->user
        ]);
    }


    //*********************************************************** *//
    //                  CRUD                                        //
    //*********************************************************** *//

    //CREAR USUARIO 
    function crearUsuario(){

        error_log('USER_CONTROLLER::crearUsuario -> ');


        // verifica si existe los valores minimos necesarios para crear un usuario
        if($this->existPost(['cedula','nombre', 'apellido1','apellido2','rol'])){

            //Valores obligatorios
            $cedula = $this->getPost('cedula');
            $nombre = $this->getPost('nombre');
            $apellido1 = $this->getPost('apellido1');
            $apellido2 = $this->getPost('apellido2');
            $rol = $this->getPost('rol');

 
            // validacion de los valores obligatorios recibidos
            if($cedula == '' || empty ($cedula) || $nombre == '' || empty($nombre) 
                || $apellido1 == '' || empty($apellido1) || $apellido2 == '' || empty($apellido2) 
                || $rol == '' || empty($rol) ){

                $this->redirect('user', ['error' => ErrorMessages::ERROR_USER_NEWUSER_EMPTY]);
            }


            //verifica si el usuario ya existe
            $user = new UserModel();

            if ($user->exists($cedula)) {
                // si ya existe la cedula
                $this->redirect('user', ['error' => ErrorMessages::ERROR_USER_NEWUSER_EXISTS]);
            }
            
            


            if ($rol != 'construccion') {
                # informacion obligatoria para los que acceden el sistema
                $email = $this->getPost('email');
                $contrasena = $this->getPost('contrasena');
                //$confcontrasena = $this->getPost('confcontrasena');//confirmacion hecha en la vista

                //guardando valores requeridos para estos roles
                $user->setEmail($email);
                $user->setContrasena($contrasena);
                
            }
            
            //valores opcionales
            $telefono = $this->getPost('telefono');
            $direccion = $this->getPost('direccion');
            $cuentaBancaria = $this->getPost('cuentaBancaria');


            // hacer procedimiento para llenar info usuario?? sino, llenar las variables aqui.
            // se llena la info del usuario
            $user->setCedula($cedula);
            $user->setNombre($nombre);
            $user->setApellido1($apellido1);
            $user->setApellido2($apellido2);
            $user->setRol($rol); 

            //llena variables opcionales
            $user->setTelefono($telefono);
            $user->setDireccion($direccion);
            $user->setCuentaBancaria($cuentaBancaria);

            
            
            //GUARDA al usuario en la BD
            if ($user->save()){
                error_log('USER_CONTROLLER::newUser -> Cedula no existe, y se ha creado el usuario');
                // retorna al index, despliega mensaje de exito, despues de insertar a DB el usuario
                $this->redirect('user', ['success' => SuccessMessages::SUCCESS_USER_NEWUSER]);
            }else{
                $this->redirect('user', ['error' => ErrorMessages::ERROR_USER_NEWUSER]);
            }
            
            

            

        }else{
            // sino existe
            $this->redirect('user', ['error' => ErrorMessages::ERROR_USER_NEWUSER]);
        }
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


    function getUserJSON($userArray){
        error_log('USER_CONTROLLER::getUserJSON()');

        
        header("HTTP/1.1 200 OK");
        header('Content-Type: application/json');
        echo json_encode($userArray);

    }




    //actualiza nombre de usuario
    function updateName(){
        if(!$this->existPOST(['name'])){
            $this->redirect('user', ['error' => ErrorMessages::ERROR_USER_UPDATENAME]);
            return;
        }

        $name = $this->getPost('name');//buscamos por nombre

        if(empty($name) || $name == NULL){
            $this->redirect('user', ['error' => ErrorMessages::ERROR_USER_UPDATENAME]);
            return;
        }
        
        $this->user->setNombre($name);
        if($this->user->update()){
            $this->redirect('user', ['success' => SuccessMessages::SUCCESS_USER_UPDATENAME]);
        }else{
            //error
        }
    }


    //actualiza el password del usuario
    function updatePassword(){
        if(!$this->existPOST(['current_password', 'new_password'])){//sino existe
            $this->redirect('user', ['error' => ErrorMessages::ERROR_USER_UPDATEPASSWORD]);
            return;
        }

        $current = $this->getPost('current_password');
        $new     = $this->getPost('new_password');

        if(empty($current) || empty($new)){//si alguno esta vacio
            $this->redirect('user', ['error' => ErrorMessages::ERROR_USER_UPDATEPASSWORD_EMPTY]);
            return;
        }

        if($current === $new){//si son iguales
            $this->redirect('user', ['error' => ErrorMessages::ERROR_USER_UPDATEPASSWORD_THESAME]);
            return;
        }

        //validar que el current es el mismo que el guardado
        $newHash = $this->model->comparePasswords($current, $this->user->getCedula());//metodo de userModel
        if($newHash){
            //si lo es actualizar con el nuevo
            $this->user->setContrasena($new);
            
            if($this->user->update()){//si se actualizo
                $this->redirect('user', ['success' => SuccessMessages::SUCCESS_USER_UPDATEPASSWORD]);
            }else{
                //error
                $this->redirect('user', ['error' => ErrorMessages::ERROR_USER_UPDATEPASSWORD]);
            }
        }else{
            //si el hash es falso, password actual incorrecto
            $this->redirect('user', ['error' => ErrorMessages::ERROR_USER_UPDATEPASSWORD]);
            return;
        }
    }


    //Codigo para funcionalidad futura
    function updatePhoto(){
        //CAMBIOS: cambie la variable foto a photo
        error_log("USERCONTROLLER::updatePhoto() started");

        if(!isset($_FILES['photo'])){//si no existe
            $this->redirect('user', ['error' => ErrorMessages::ERROR_USER_UPDATEPHOTO]);
            return;
        }
        $photo = $_FILES['photo'];

        $target_dir = "public/img/photos/";
        $extarr = explode('.',$photo["name"]);//divide el nombre en el punto en un arreglo
        $filename = $extarr[sizeof($extarr)-2];//quita extencion al archivo
        $ext = $extarr[sizeof($extarr)-1]; //se almacena la extencion del archivo
        $hash = md5(Date('Ymdgi') . $filename) . '.' . $ext; //crea hash del nombre del archivo usando la fecha
        $target_file = $target_dir . $hash;//se crea un nombre unico del archivo para evitar sobreescribir archivo
        $uploadOk = false;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));//vuelve todo el archivo a minuscula
        
        $check = getimagesize($photo["tmp_name"]);//tmp_name = ubicacion temporal
        if($check !== false) {
            //echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = true;
        } else {
            //echo "File is not an image.";
            $uploadOk = false;
        }

        if ($uploadOk == false) {
            //echo "Sorry, your file was not uploaded.";
            $this->redirect('user', ['error' => ErrorMessages::ERROR_USER_UPDATEPHOTO_FORMAT]);
        // if everything is ok, try to upload file
            return;
        } else {

            try {
                //code...
                if (move_uploaded_file($photo["tmp_name"], $target_file)) {//si mueve archivo
                    $this->user->setFoto($hash); //$hash tiene el nombre del archivo con la extencion
                    $this->user->update();
                    $this->redirect('user', ['success' => SuccessMessages::SUCCESS_USER_UPDATEPHOTO]);
                    return;
    
                } else {//sino se mueve el archivo
                    $this->redirect('user', ['error' => ErrorMessages::ERROR_USER_UPDATEPHOTO]);
                    return;
                }

            } catch (Throwable $e) {
                //throw $th;
                error_log('USERCONTROLLER::updatePhoto->Exception ' . $e);
            }
           
        }
        
    }





}



?>