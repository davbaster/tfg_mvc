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

                    //var_dump($item);//Sirve para Debug y verificacion de row extraido de la DB

                    $user = new UserModel();
                    // rellenando el usuario con la funcion que recibe un arreglo
                    $user->from($item);


                    // validamos password ingresado con password almacenado
                    // error_log('LoginModel-> contrasena provista: ' . $contrasena . ' Contrasena en DB: ' . $user->getContrasena());
                    if (password_verify($contrasena, $user->getContrasena())) {
                        
                        error_log('LoginModel::login->Contrasena se verifico y es correcta');
                        // regreso objeto userModel
                        //var_dump($user);//DEBUGGING: verificar si user esta lleno y a quien se lo estamos enviando

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

        function holaMundo(){
            error_log('Model::LoginModel-> El objeto LoginModel si se esta creando y puede correr funciones ');//Debugging: solamente usada para debug
        }
    }
?>