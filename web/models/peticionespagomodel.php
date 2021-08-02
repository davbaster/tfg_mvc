<?php

class PeticionesPagoModel extends Model implements IModel {


    private $id; //id de peticionPago
    private $cedula; //usuario con rol de contratista, usuario que creo la peticion de pago
    private $fechaCreacion;//fecha en que se crea la peticion de pago
    private $idContrato; //TODO se va a usar si se crea modulo contratos
    private $monto;
    private $estado; //pending, parcial, pagado //TODO revisar si esta nomencleatura es la mejor
   
    
    private $pagos;//array que almacena los ids de pagos hechos //TODO ponerlo en la DB, revisar si esta nomencleatura es la mejor
    
    //private $listaUsuariosPagar;//TODO lista de peticiones de pago a trabajadores que estan incluidas en la peticion de pago (planilla)
                                //cuando se aprueba la peticion de pago, todas ellas se aprueban automaticamente
    private $detalles;//horas trabajadas por usuario, detalles adicionales

    public function __construct(){
        parent::__construct();
    }


    public function save(){
        try{
            $query = $this->prepare('INSERT INTO peticiones_pago (id, cedula, fecha_creacion, id_contrato, monto, estado_pago, estado, detalles) 
                                    VALUES(:id,:cedula, :fechaCreacion, :idContrato, :monto, :estadoPago, :estado, :detalles)');
            $query->execute([
                'id' => $this->id,
                'cedula' => $this->cedula, 
                'fechaCreacion' => $this->fechaCreacion,
                'idContrato' => $this->idContrato,
                'monto' => $this->monto,
                'estado' => $this->estado,
                'aprobado' => $this->aprobado,
                'detalles' => $this->detalles
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
                $item = new PeticionesPagoModel();
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
            $query = $this->db->connect()->prepare('UPDATE peticiones_pago SET 
                                                    cedula = :cedula, 
                                                    fecha_creacion = :fechaCreacion,  
                                                    id_contrato = :idContrato, 
                                                    monto = :monto, 
                                                    estado_pago = :estadoPago, 
                                                    estado = :estado,  
                                                    detalles = :detalles WHERE id = :id');
            $query->execute([
                'id' => $this->id,
                'cedula' => $this->cedula, 
                'fechaCreacion' => $this->fechaCreacion,
                'idContrato' => $this->idContrato,
                'monto' => $this->monto,
                'estado' => $this->estado,
                'aprobado' => $this->aprobado,
                'detalles' => $this->detalles
            ]);
            return true;
        }catch(PDOException $e){
            echo $e;
            return false;
        }
    }



    //
    public function from($array){

        $this->setId                ($array['id']) ;
        $this->setCedula            ($array['cedula'] ) ;
        $this->setFechaCreacion     ( $array['fecha_creacion'] ) ;
        $this->setIdContrato        ( $array['id_contrato'] ) ;
        $this->setMonto             ( $array['monto'] ) ;
        $this->setEstadoPago        ($array['estado_pago'] ) ;
        $this->setEstado            ( $array['estado'] ) ;
        $this->setDetalles          ( $array['detalles'] ) ;

    }


    //existe la peticion de pago?
    public function exists($id){
        try{
            $query = $this->prepare('SELECT id FROM peticiones_pago WHERE id = :id');
            $query->execute( ['id' => $id]);
            
            if($query->rowCount() > 0){
                error_log('PeticionesPagoModel::exists() => true');
                return true;
            }else{
                error_log('PeticionesPagoModel::exists() => false');
                return false;
            }
        }catch(PDOException $e){
            error_log($e);
            return false;
        }
    }


    //saca de la base de datos todos las peticiones de pagos que esten pendientes de aprobacion
    //retorna arreglo de pagos pendientes
    public function getPeticionesPendientesAprobacion(){

        $items = [];//arreglo de objetos de tipo pago

        $estado = "open";//open, aprobado, denegado
        //$estadoPago = "pending"; //pending, parcial, pagado

        try{
            
            $query = $this->prepare('SELECT * FROM peticiones_pago WHERE estado = :estado');
            //$query = $this->prepare('SELECT * FROM pagos WHERE id = :id');
            $query->execute([ 'estado' => $estado]);

            //mientras que exista un objeto no null
            while($p = $query->fetch(PDO::FETCH_ASSOC)){
                $item = new PeticionesPagoModel();
                //rellena objeto con informacion
                $item->from($p); 
                
                array_push($items, $item);
            }

            return $items;

        }catch(PDOException $e){
            return false;
        }
    }


    //saca de la base de datos todos las peticiones de pagos que esten pendientes de pago
    //retorna arreglo de peticiones de pago pendientes
    public function getPeticionesPendientesPago(){

        $items = [];//arreglo de objetos de tipo pago

        $estado = "aprobado";//open, aprobado, denegado
        $estadoPago = "pending"; //pending, pagado

        try{
            
            $query = $this->prepare('SELECT * FROM peticiones_pago WHERE estado = :estado AND estado_pago = :estadopago');
            //$query = $this->prepare('SELECT * FROM pagos WHERE id = :id');
            $query->execute([ 'estado' => $estado,
                            'estadopago' => $estadoPago]);

            //mientras que exista un objeto no null
            while($p = $query->fetch(PDO::FETCH_ASSOC)){
                $item = new PeticionesPagoModel();
                //rellena objeto con informacion
                $item->from($p); 
                
                array_push($items, $item);
            }

            return $items;

        }catch(PDOException $e){
            return false;
        }
    }



    //setters

    public function setId($id){$this->id = $id;}
    public function setCedula($cedula){$this->cedula = $cedula;}
    public function setFechaCreacion($fechaCreacion){$this->fechaCreacion = $fechaCreacion;} //color
    public function setIdContrato($idContrato){$this->idContrato = $idContrato;}
    public function setMonto($monto){$this->monto = $monto;}
    public function setEstadoPago($estadoPago){$this->estadoPago = $estadoPago;}
    public function setEstado($estado){$this->estado = $estado;}
    public function setDetalles($detalles){$this->detalles = $detalles;}

    public function setListaUsuariosPagar($listaUsuariosPagar){$this->listaUsuariosPagar = $listaUsuariosPagar;}//TODO guarda cada uno de los pagos a trabajadores, 
                                                                                            //deberian ser pagos con estado = sin pagar, deberia recibir un array




    //getters
    public function getId(){return $this->id;}
    public function getCedula(){ return $this->cedula;}
    public function getFechaCreacion(){ return $this->fechaCreacion;}
    public function getIdContrato(){ return $this->idContrato;}
    public function getMonto(){ return $this->monto;}
    public function getEstadoPago(){ return $this->estadoPago;}
    public function getEstado(){ return $this->estado;}
    public function getDetalles(){ return $this->detalles;}

    public function getListaUsuariosPagar(){ return $this->listaUsuariosPagar;} 





}




?>