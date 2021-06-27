<?php

    // require_once 'config.php';
    require_once 'libs/database.php';

    class Auth extends Database{

        // Registra un nuevo usuario
        public function addUser($cedula,$nombre,$apellido1, $apellido2,$telefono,$direccion,$cuentaBancaria,$email,$contrasena,$token){


            $sql = "INSERT INTO users (cedula, nombre, apellido1,apellido2,telefono,direccion,cuentaBancaria,email,contrasena,token) VALUES (:cedula,:nombre,:apellido1,:apellido2,:telefono,:direccion,:cuentaBancaria,:email,:contrasena,:token)";
            $stmt = $this->conn->prepare($sql);
            $resultado = $stmt->execute(['cedula'=>$cedula, 'nombre'=>$nombre,'apellido1'=>$apellido1 ,'apellido2'=>$apellido2,'telefono'=>$telefono,'direccion'=>$direccion,'cuentaBancaria'=>$cuentaBancaria,'email'=>$email,'contrasena'=>$contrasena,'token'=>$token]);
            
            //obteniendo ID de fila
			$id = $dbh->lastInsertId();
            echo "mostrando... ".$id;

            return $resultado;

        }



        // revisa si ya existe el usuario
        public function user_exist($cedula){
            $sql = "SELECT cedula FROM users WHERE cedula = :cedula";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(['cedula'=>$cedula]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result;
        }

        //loguea usuario existente
        public function login($cedula){
            $sql = "SELECT cedula, contrasena FROM users WHERE cedula = :cedula AND deleted != 0";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(['cedula'=>$cedula]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            return $row;

        }

        // current user in session
        public function currentUser($cedula){
            $sql = "SELECT * FROM users WHERE cedula = :cedula AND deleted != 0";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(['cedula'=>$cedula]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            return $row;
        }



    } 



?>