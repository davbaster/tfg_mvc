<?php
    require_once 'models/usermodel.php';

    class LoginModel extends Model {

        function __construct(){
            parent::__construct();
            
        }

        // 
        function login($cedula, $contrasena){
            try{

                $query = $this->prepare('SELECT * FROM users WHERE cedula = :cedula');
                $query->execute(['cedula' => $cedula]);

                // validacion de si hay solo un usuario con la misma cedula
                if ($query->rowCount() == 1) {
                    //item se vuelve un arreglo que contiene la info de un usuario 
                    $item = $query->fetch(PDO::FETCH_ASSOC);

                    $user = new UserModel();
                    // rellenando el usuario con la funcion que recibe un arreglo
                    $user->from($item);


                    // validamos password ingresado con password almacenado
                    if (password_verify($contrasena, $user->getContrasena())) {
                        
                        error_log('LoginModel::login->sucess');
                        // regreso objeto userModel
                        return $user;
                    }else{
                        error_log('LoginModel::login->CONTRASENA NO ES IGUAL');
                        return NULL;
                    }
                }

            }catch(PDOException $e){
                error_log('php 14. LoginModel::login->exception ' . $e);
                return NULL;

            }
        }
    }
?>