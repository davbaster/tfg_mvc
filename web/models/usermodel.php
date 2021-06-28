<?php

class UserModel extends Model implements IModel {

    private $id;
    private $username;
    private $password;
    private $role;
    private $budget;
    private $photo;
    private $name;


    private $cedula;
    private $nombre;
    private $apellido1;
    private $apellido2;
    private $telefono;
    private $direccion;
    private $cuentaBancaria;
    private $email;
    private $contrasena;

    public function __construct(){

        parent::__construct();

        $this->id ='';
        $this->username ='';
        $this->password ='';
        $this->budget =0.0;
        $this->photo ='';
        $this->name ='';
        

        $this->cedula ='';
        $this->nombre ='';
        $this->apellido1 ='';
        $this->apellido2 ='';
        $this->telefono ='';
        $this->direccion ='';
        $this->cuentaBancaria ='';
        $this->email ='';
        $this->contrasena ='';
        $this->role = '';
        



    }

    public function save(){
        try{
            // preparando la sentencia
            $query = $this->prepare('INSERT INTO users (cedula, nombre, apellido1,apellido2,telefono,direccion,cuentaBancaria,email,contrasena,role) VALUES (:cedula,:nombre,:apellido1,:apellido2,:telefono,:direccion,:cuentaBancaria,:email,:contrasena,:role)');

            // referenciando los placeholders
            $query->execute([
                'cedula' => $this->cedula,
                'nombre' => $this->nombre,
                'apellido1' => $this->apellido1,
                'apellido2' => $this->apellido2,
                'telefono' => $this->telefono,
                'direccion' => $this->direccion,
                'cuentaBancaria' => $this->cuentaBancaria,
                'email' => $this->email,
                'contrasena' => $this->contrasena,
                'role' => $this->role
            ]);

            return true;
        }catch(PDOException $e){
            error_log('USERMODEL::save->PDOException ' . $e);
            return false;
        }
    }

    // regresa un arreglo de usuarios
    public function getAll(){
        $items = [];

        try {

            $query = $this->query('SELECT * FROM users');

            // p es puntero elemento actual
            // PDO::FETCH_ASSOC devuelve un objeto transformado
            while($p = $query->fetch(PDO::FETCH_ASSOC)){
                $item = new UserModel();

                $item->setCedula($p['cedula']);
                $item->setNombre($p['nombre']);
                $item->setApellido1($p['apellido1']);
                $item->setApellido2($p['apellido2']);
                $item->setTelefono($p['telefono']);
                $item->setDireccion($p['direccion']);
                $item->setCuentaBancaria($p['cuentaBancaria']);
                $item->setEmail($p['email']);
                $item->setContrasena($p['contrasena']);
                $item->setRole($p['role']);
            }

        }catch(PDOException $e){
            error_log('USERMODEL::getAll->PDOException ' . $e);
            return false;

        }
    }


    public function get($id){}
    public function delete($id){}
    public function update(){}
    public function from($array){}


    // setters and getters
    public function setId($id){
        $this->id = $id;
    }

    public function setRole($role){ $this->role = $role;}
    public function setBudget($budget){ $this->budget = $budget;}
    public function setPhoto($photo){ $this->photo = $photo;}
    public function setName($name){ $this->name = $name;}


    public function setCedula($cedula){ $this->cedula = $cedula;}
    public function setNombre($nombre){ $this->nombre = $nombre;}
    public function setApellido1($apellido1){ $this->apellido1 = $apellido1;}
    public function setApellido2($apellido2){ $this->apellido2 = $apellido2;}
    public function setTelefono($telefono){ $this->telefono = $telefono;}
    public function setDireccion($direccion){ $this->direccion = $direccion;}
    public function setCuentaBancaria($cuentaBancaria){ $this->cuentaBancaria = $cuentaBancaria;}
    public function setEmail($email){ $this->email = $email;}
    public function setContrasena($contrasena){ 
        $this->contrasena = $this->getHashedPassword($contrasena);
    }

    private function getHashedPassword (){
        
    }

    // getters
    public function getId(){
        return $this->id;
    }

    public function getRole(){ return $this->role;}
    public function getBudget(){return $this->budget;}
    public function getPhoto(){return $this->photo;}
    public function getName(){ return $this->name;}


    public function getCedula(){ return $this->cedula;}
    public function getNombre(){ return $this->nombre;}
    public function getApellido1(){ return $this->apellido1;}
    public function getApellido2(){ return $this->apellido2;}
    public function getTelefono(){ return $this->telefono;}
    public function getDireccion(){ return $this->direccion;}
    public function getCuentaBancaria(){ return $this->cuentaBancaria;}
    public function getEmail(){ return $this->email;}
    public function getContrasena(){ return $this->contrasena;}


}

?>