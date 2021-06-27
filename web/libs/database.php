<?php


class Database {

    private $host;
    private $db;
    // private $dsn = "mysql:host=localhost;dbname=db_user_system";
    // private $dbuser = "root";
    // private $dbpass = "";
    private $dbuser;
    private $dbpass;
    private $charset;

    public $conn;


    public function __construct(){

        // usando constantes definidas en config.php
        $this->host = constant('HOST');
        $this->db = constant('DB');
        $this->dbuser = constant('USER');
        $this->dbpass = constant('PASSWORD');
        $this->charset = constant('CHARSET');
    }

    public function connect(){
        try{
            $connection = "mysql:host=" . $this->host . ";dbname=" . $this->db . 
            $options = [
                PDO::ATTR_ERRMODE               => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES      => false,
            ];
            
            $this->conn = new PDO($connection, $this->dbuser, $this->dbpass, $options);
            error_log('Conexion a BD exitosa');
            return $this->conn;

        }catch(PDOException $e){
            error_log('Error connection: ' . $e->getMessage());
        }
    }


    // public function __construct(){
        
    //     try{
    //         $this->conn = new PDO($this->dsn,$this->dbuser,$this->dbpass);

    //         //echo 'Conectado a la base de datos exitosamente.';

    //     }catch(PDOException $e){
    //         echo 'Error : '.$e->getMessage(); 
    //     }

    //     return $this->conn;
    // }

    // verifica input
    public function test_input($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    // error sucess message alert
    public function showMessage($type, $message){
        return '<div class="alert alert-'.$type.' alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong class="text-center">'.$message.'</strong>
                </div>';
    }
}



?>