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


    //
    function updateBudget(){

        //si no existe
        if(!$this->existPOST('budget')){
            $this->redirect('user', ['error' => ErrorMessages::ERROR_USER_UPDATEBUDGET]);
            return;
        }

        $budget = $this->getPost('budget');

        if(empty($budget) || $budget === 0 || $budget < 0){//valida sea vacio, igual o menor que cero
            $this->redirect('user', ['error' => ErrorMessages::ERROR_USER_UPDATEBUDGET_EMPTY]);
            return;
        }
    
        $this->user->setBudget($budget);//asignando budget al usuario

        if($this->user->update()){//si se actualiza
            $this->redirect('user', ['success' => SuccessMessages::SUCCESS_USER_UPDATEBUDGET]);
        }else{
            //error
        }
    }



    //
    function updateName(){
        if(!$this->existPOST('nombre')){
            $this->redirect('user', ['error' => ErrorMessages::ERROR_USER_UPDATEBUDGET]);
            return;
        }

        $name = $this->getPost('nombre');//buscamos por nombre

        if(empty($name) || $name == NULL){
            $this->redirect('user', ['error' => ErrorMessages::ERROR_USER_UPDATEBUDGET]);
            return;
        }
        
        $this->user->setNombre($name);
        if($this->user->update()){
            $this->redirect('user', ['success' => SuccessMessages::SUCCESS_USER_UPDATEBUDGET]);
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
            $this->redirect('user', ['error' => ErrorMessages::ERROR_USER_UPDATEPASSWORD_ISNOTTHESAME]);
            return;
        }

        //validar que el current es el mismo que el guardado
        $newHash = $this->model->comparePasswords($current, $this->user->getId());//metodo de userModel
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
            //si el hash es falso, password incorrecto
            $this->redirect('user', ['error' => ErrorMessages::ERROR_USER_UPDATEPASSWORD]);
            return;
        }
    }


    //
    function updatePhoto(){
        error_log("USERCONTROLLER::updatePhoto() started");

        if(!isset($_FILES['foto'])){//si no existe
            $this->redirect('user', ['error' => ErrorMessages::ERROR_USER_UPDATEPHOTO]);
            return;
        }
        $photo = $_FILES['foto'];

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