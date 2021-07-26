<?php

class PeticionModel extends Model implements IModel {


    private $id;
    private $name;
    private $color;

    private $idUsuario; //usuario con rol de contratista, usuario que creo la peticion de pago
    private $estado; //pagado, pendiente de pago, pago parcial
    private $fecha;
    private $fechaPago;
    private $monto;
    private $listaUsuarios;//lista de usuarios que van a recibir un pago
    private $detalles;//horas trabajadas por usuario, detalles adicionales

    public function __construct(){
        parent::__construct();
    }


    public function save(){
        try{
            $query = $this->prepare('INSERT INTO peticiones_pago (name, color) VALUES(:name, :color)');
            $query->execute([
                'name' => $this->name, 
                'color' => $this->color
            ]);
            if($query->rowCount()) return true;

            return false;
        }catch(PDOException $e){
            return false;
        }
    }


    //muestra todas las peticiones de pago
    //apenas vista admin
    public function getAll(){
        $items = [];
    
        try{
            $query = $this->query('SELECT * FROM peticiones_pago');
    
            while($p = $query->fetch(PDO::FETCH_ASSOC)){
                $item = new PeticionModel();
                $item->from($p); 
                
                array_push($items, $item);
            }
    
            return $items;
    
        }catch(PDOException $e){
            echo $e;
            return NULL;
        }
    }


    //
    public function get($id){
        try{
            $query = $this->prepare('SELECT * FROM peticiones_pago WHERE id = :id');
            $query->execute([ 'id' => $id]);
            $peticion = $query->fetch(PDO::FETCH_ASSOC);

            $this->from($peticion);//rellena objeto

            return $this;
        }catch(PDOException $e){
            return NULL;
        }
    }


    //
    public function delete($id){
        try{
            $query = $this->db->connect()->prepare('DELETE FROM peticiones_pago WHERE id = :id');
            $query->execute([ 'id' => $id]);
            return true;
        }catch(PDOException $e){
            echo $e;
            return false;
        }
    }


    //
    public function update(){
        try{
            $query = $this->db->connect()->prepare('UPDATE peticiones_pago SET name = :name, color = :color WHERE id = :id');
            $query->execute([
                'name' => $this->name, 
                'color' => $this->color
            ]);
            return true;
        }catch(PDOException $e){
            echo $e;
            return false;
        }
    }


    //
    public function from($array){
        $this->id = $array['id'];
        $this->name = $array['name'];
        $this->color = $array['color'];
    }


    //existe la peticion de pago?
    public function exists($name){
        try{
            $query = $this->prepare('SELECT name FROM peticiones_pago WHERE name = :name');
            $query->execute( ['name' => $name]);
            
            if($query->rowCount() > 0){
                error_log('PeticionModel::exists() => true');
                return true;
            }else{
                error_log('PeticionModel::exists() => false');
                return false;
            }
        }catch(PDOException $e){
            error_log($e);
            return false;
        }
    }



    //setters

    public function setId($id){$this->id = $id;}
    public function setName($name){$this->name = $name;}
    public function setColor($color){$this->color = $color;}

    //getters
    public function getId(){return $this->id;}
    public function getName(){ return $this->name;}
    public function getColor(){ return $this->color;}




}




?>