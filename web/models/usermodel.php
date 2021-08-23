<?php

class UserModel extends Model implements IModel {


    private $cedula;
    private $nombre;
    private $apellido1;
    private $apellido2;
    private $telefono;
    private $direccion;
    private $cuentaBancaria;
    private $email;
    private $contrasena;
    private $rol;
    private $foto;

    public function __construct(){

        parent::__construct();

   
        $this->cedula ='';
        $this->nombre ='';
        $this->apellido1 ='';
        $this->apellido2 ='';
        $this->telefono ='';
        $this->direccion ='';
        $this->cuentaBancaria ='';
        $this->email ='';
        $this->contrasena ='';
        $this->rol = '';
        $this->foto = '';
        



    }

    public function save(){
        try{
            // preparando la sentencia
            $query = $this->prepare('INSERT INTO users (cedula, nombre, apellido1,apellido2,telefono,direccion,cuentaBancaria,email,contrasena,rol,foto) VALUES (:cedula,:nombre,:apellido1,:apellido2,:telefono,:direccion,:cuentaBancaria,:email,:contrasena,:rol,:foto)');

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
                'rol' => $this->rol, //puede ponerse como enum, buscar si se inserta diferente
                'foto' => $this->foto
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
            //setPasswordSinHash para cuando llenamos un usuario con la info de la BD
            while($user = $query->fetch(PDO::FETCH_ASSOC)){
                // $this = new UserModel(); si se usa da PHP Fatal error:  Cannot re-assign $this
                $nuevoUsuario = new UserModel();

                // cambie $this por $nuevoUsuario
                $nuevoUsuario->setCedula($user['cedula']);
                $nuevoUsuario->setNombre($user['nombre']);
                $nuevoUsuario->setApellido1($user['apellido1']);
                $nuevoUsuario->setApellido2($user['apellido2']);
                $nuevoUsuario->setTelefono($user['telefono']);
                $nuevoUsuario->setDireccion($user['direccion']);
                $nuevoUsuario->setCuentaBancaria($user['cuentaBancaria']);
                $nuevoUsuario->setEmail($user['email']);
                //$nuevoUsuario->setContrasena($user['contrasena']);//esta volviendo a hashear la contrasena
                $this->setContrasenaSinHash($user['contrasena']);
                $nuevoUsuario->setRol($user['rol']);
                $nuevoUsuario->setFoto($user['foto']);

                // guarda el usuario en el array $items
                array_push($items, $nuevoUsuario);
            }

            return $items;

        }catch(PDOException $e){
            error_log('USERMODEL::getAll->PDOException ' . $e);
            return false;

        }
    }

    // busca cedula en BD
    //setPasswordSinHash para cuando llenamos un usuario con la info de la BD
    public function get($cedula){

        try {

            $query = $this->prepare('SELECT * FROM users WHERE cedula = :cedula');
            // ejecutando sentencia
            $query->execute([
                'cedula' => $cedula
            ]);

            $user = $query->fetch(PDO::FETCH_ASSOC);

            if(!$user){//si el resultado es vacio
                return $user;
            }

            $this->from($user);//rellena objeto con la informacion de la BD



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
            //TODO something does not work if you try to add cedula = :cedula and 'cedula' => $this->cedula,
            $query = $this->prepare('UPDATE users SET 
                                    nombre = :nombre,
                                    apellido1 = :apellido1,
                                    apellido2 = :apellido2,
                                    telefono = :telefono,
                                    direccion = :direccion,
                                    cuentaBancaria = :cuentaBancaria,
                                    email = :email,
                                    contrasena = :contrasena,
                                    rol = :rol,
                                    foto = :foto
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
                'contrasena' => $this->contrasena, //password ya en formato de hash //posible que tengamos que aplicar hash aqui
                'rol' => $this->rol,
                'foto' => $this->foto
            ]);

                return true;

        }catch(PDOException $e){
            error_log('USERMODEL::update->PDOException ' . $e);
            return false;

        }
    }

    // se pasa un arreglo con informacion y este asigna sus contenidos al objeto $this
    // 
    public function from($array){


        //var_dump($array); //Debugging line: con esto reviso si se mando el array lleno 

        $this->setCedula          ($array['cedula']) ;
        $this->setNombre          ($array['nombre'] ) ;
        $this->setApellido1       ( $array['apellido1'] ) ;
        $this->setApellido2       ( $array['apellido2'] ) ;
        $this->setTelefono        ( $array['telefono'] ) ;
        $this->setDireccion       ($array['direccion'] ) ;
        $this->setCuentaBancaria  ( $array['cuentaBancaria'] ) ;
        $this->setEmail           ( $array['email'] ) ;
        //error_log('USERMODEL::Contrasena recibida en array[contrasena] ' . $array['contrasena']);
        $this->contrasena    =     $array['contrasena'];
        $this->setRol             ( $array['rol'] ) ;
        $this->setFoto            ( $array['foto'] ) ;
        //$this->fechaIngreso            ( $array['fechaIngreso'] ) ;
        //$this->estado            ( $array['estado'] ) ;

    }

    //Mete un objeto a un arreglo
    public function toArray(){
        $array = [];
        $array['cedula']            = $this->cedula;
        $array['nombre']            = $this->nombre;
        $array['apellido1']         = $this->apellido1;
        $array['apellido2']         = $this->apellido2;
        $array['telefono']          = $this->telefono;
        $array['direccion']         = $this->direccion;
        $array['cuentaBancaria']    = $this->cuentaBancaria;
        $array['email']             = $this->email;
        $array['contrasena']        = $this->contrasena;
        $array['rol']               = $this->rol;
        $array['foto']              = $this->foto;
        //$array['fechaIngreso'] = $this->fechaIngreso;
        //$array['estado'] = $this->estado;

        return $array;
    }


    // verifica si el usuario existe
    public function exists($cedula){
        try{
            $query = $this->prepare('SELECT cedula FROM users WHERE cedula = :cedula');
            $query->execute(['cedula' => $cedula]);

            if($query->rowCount() > 0){
                // hay usuarios con la cedula ingresada
                return true;
            }else{
                return false;
            }

        }catch (PDOException $e){
            error_log('USERMODEL::exists->PDOException ' . $e);
            return false;
        }
    }

    // compara passwords
    public function comparePasswords($contrasena, $cedula){
        try{

            $user = $this->get($cedula);
            // error_log('USERMODEL::comparePasswords->contrasena en BD: ' . $user->getContrasena());
            // error_log('USERMODEL::comparePasswords->contrasena data : ' . $this->getHashedPassword($contrasena) );
            return password_verify($contrasena, $user->getContrasena()); 



        }catch(PDOException $e){
            error_log('USERMODEL::comparePasswords->PDOException ' . $e);
            return false;
        }
    }


    // setters
    public function setId($id){
        $this->id = $id;
    }

    
    public function setBudget($budget){ $this->budget = $budget;}//TODO no usado todavia, podria ser setContrato
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
        //error_log('USERMODEL::setContrasena-> Contrasena recibida: ' . $contrasena);
        $this->contrasena = $this->getHashedPassword($contrasena);
        //$this->contrasena = $contrasena;
    }
    public function setContrasenaSinHash($contrasena){ 
        
        $this->contrasena = $contrasena;
        
    }
    public function setRol($rol){ $this->rol = $rol;}
    public function setFoto($foto){ $this->foto = $foto;}




    // getters
    public function getId(){
        // vericar que tiene ID y quien lo usa, porque creo que esta sin uso
        return $this->id;
    }

    public function getBudget(){return $this->budget;} //TODO podria ser getContrato

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
    public function getRol(){ return $this->rol;}
    public function getFoto(){return $this->foto;}

    // encrypta password para ser almacenado en la base de datos
    private function getHashedPassword ($password){
        // costo entre mas alto mas gasto de cpu y mayor seguridad
        //return password_hash($password, PASSWORD_DEFAULT, ['cost' => 10]);
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        //error_log('USERMODEL::hashed password-> '. $hashedPassword );
        return $hashedPassword;

    }


}

?>