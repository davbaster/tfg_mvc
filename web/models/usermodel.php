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

            // user es puntero elemento actual
            // PDO::FETCH_ASSOC devuelve un objeto transformado
            while($user = $query->fetch(PDO::FETCH_ASSOC)){
                $this = new UserModel();

                $this->setCedula($user['cedula']);
                $this->setNombre($user['nombre']);
                $this->setApellido1($user['apellido1']);
                $this->setApellido2($user['apellido2']);
                $this->setTelefono($user['telefono']);
                $this->setDireccion($user['direccion']);
                $this->setCuentaBancaria($user['cuentaBancaria']);
                $this->setEmail($user['email']);
                $this->setContrasena($user['contrasena']);
                $this->setRole($user['role']);

                // guarda el usuario en el array $items
                array_push($items, $this);
            }

            return $items;

        }catch(PDOException $e){
            error_log('USERMODEL::getAll->PDOException ' . $e);
            return false;

        }
    }

    // busca cedula en BD
    public function get($cedula){

        try {

            $query = $this->prepare('SELECT * FROM users WHERE cedula = :cedula');
            // ejecutando sentencia
            $query->execute([
                'cedula' => $cedula
            ]);

            $user = $query->fetch(PDO::FETCH_ASSOC);

            // user es puntero elemento actual
            // PDO::FETCH_ASSOC devuelve un objeto transformado

            // llenando la informacion del usuario en el objeto this
            $this->setCedula($user['cedula']);
            $this->setNombre($user['nombre']);
            $this->setApellido1($user['apellido1']);
            $this->setApellido2($user['apellido2']);
            $this->setTelefono($user['telefono']);
            $this->setDireccion($user['direccion']);
            $this->setCuentaBancaria($user['cuentaBancaria']);
            $this->setEmail($user['email']);
            $this->setContrasena($user['contrasena']);
            $this->setRole($user['role']);



            return $this;

        }catch(PDOException $e){
            error_log('USERMODEL::get->PDOException ' . $e);
            return false;

        }

    }


    // borra un usuario dado la cedula
    public function delete($cedula){

        try {

            $query = $this->prepare('DELETE FROM users WHERE cedula = :cedula');
            // ejecutando sentencia
            $query->execute([
                'cedula' => $cedula
            ]);

            return true;

        }catch(PDOException $e){
            error_log('USERMODEL::delete->PDOException ' . $e);
            return false;

        }

    }

    // primero llamar a get(cedula), para rellenar con la informacion del usuario el objeto $this
    public function update(){

        try {

            $query = $this->prepare('UPDATE users SET cedula = :cedula,
                                    nombre = :nombre,
                                    apellido1 = :apellido1,
                                    apellido2 = :apellido2,
                                    telefono = :telefono,
                                    direccion = :direccion,
                                    cuentaBancaria = :cuentaBancaria,
                                    email = :email,
                                    contrasena = :contrasena,
                                    role = :role
                                    WHERE cedula = :cedula');
            // ejecutando sentencia
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
            error_log('USERMODEL::update->PDOException ' . $e);
            return false;

        }
    }

    // se pasa un arreglo con informacion y este los convierte en miembros
    // 
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

    // encrypta password para ser almacenado en la base de datos
    private function getHashedPassword ($password){
        // costo entre mas alto mas gasto de cpu y mayor seguridad
        return password_hash($password, PASSWORD_DEFAULT, ['cost' => 1]);

    }


}

?>