<?php
    session_start();

    require_once 'auth.php';
    $user = new Auth();

    //agrega un usuario nuevo
    if(isset($_POST['action']) && $_POST['action'] == 'agregar' ){
        // print_r($_POST);
        $cedula = $user->test_input($_POST['cedula']); 
        $name = $user->test_input($_POST['fname']);
        $apellido1 = $user->test_input($_POST['apellido1']); 
        $apellido2 = $user->test_input($_POST['apellido2']); 
        $telefono = $user->test_input($_POST['telefono']); 
        $direccion = $user->test_input($_POST['direccion']); 
        $cuenta = $user->test_input($_POST['cuentaBancaria']);
        $email = $user->test_input($_POST['email']);  
        $pass = $user->test_input($_POST['password']); 
        $token = "abcd";
        //convert password to password hash for security reasons
        $hpass = password_hash($pass, PASSWORD_DEFAULT);

        
        if($user->user_exist($cedula)){
            echo $user->showMessage('warning', 'Esta cedula ya ha sido registrada!');
        }
        else{
            //entre si puede registrar al usuario
            if($user->addUser($cedula,$name,$apellido1,$apellido2,$telefono,$direccion,$cuenta,$email,$hpass,$token)){
                
                // Esta linea de abajo manda la palabra register para que la capte la pagina
                // echo 'register';
                
                $_SESSION['user'] = $cedula;
                echo 'agregado';
            }
            else{
                echo $user->showMessage('danger', 'Algo salio mal, intente de nuevo luego');
            }
        }
        
    }


    // request para login de usuario
    if(isset($_POST['action']) && $_POST['action'] == 'login'   ){
        $cedula = $user->test_input($_POST['cedula']);
        $pass = $user->test_input($_POST['password']);
       
        $loggedInUser = $user->login($cedula);

        // si el usuario esta en la base de datos
        if($loggedInUser != null){
            //echo $loggedInUser['password'];
            //si el password escrito (pass) coincide con el password en la base de datos
            $passHash = password_hash($loggedInUser['contrasena'], PASSWORD_DEFAULT); //convierte el password a hash
            if( password_verify($pass, $loggedInUser['contrasena']) ){
            // if( password_verify($pass, $passHash) ){ //borrar cuando se inserten los usuarios usando crear usuario
                //si la casilla de recordarme esta marcada...
                if(!empty($_POST['rem'])){
                    setcookie("cedula", $cedula, time()+(30*24*60*60), '/');
                    setcookie("password", $pass, time()+(30*24*60*60), '/');

                }
                else{
                    setcookie("cedula","",1,'/');
                    setcookie("password","",1,'/');
                }
                
                $_SESSION['user'] = $cedula;

                //ECHO manda respuesta a action.js, y lo interpreta: login ajax request interpreter
                echo 'login';
                

            }
            else{
                echo $user->showMessage('danger', 'El password es incorrecto');
            }

        }
        else{
            //usuario no encontrado
            echo $user->showMessage('danger', 'El usuario no se encuentra');
        }
    }


?>