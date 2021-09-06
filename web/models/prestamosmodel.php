<?php



class PrestamosModel extends Model implements IModel {



    //************ */
    private $id;
    private $peticionPagoId;
    private $pagoId;
    private $cedula; 
    private $estado;//pendiente->autorizado->rechazado->pagado
    private $monto;
    private $fechaCreacion;//fecha en que se creo el pago
    private $fechaAprobacion;
    private $fechaPago;
    private $approver;
    private $requestedBy;
    private $detalles;
   

    //setters
    public function setId($id){ $this->id = $id; }
    public function setPeticionPagoId($peticionPagoId){ $this->peticionPagoId = $peticionPagoId; }
    public function setPagoId($pagoId){ $this->pagoId = $pagoId; }
    public function setCedula($cedula){ $this->cedula = $cedula; }
    public function setEstado($estado){ $this->estado = $estado; }
    public function setMonto($monto){ $this->monto = $monto; }
    public function setFechaCreacion($fechaCreacion){ $this->fechaCreacion = $fechaCreacion; }
    public function setFechaAprobacion($fechaAprobacion){ $this->fechaAprobacion = $fechaAprobacion; }
    public function setFechaPago($fechaPago){ $this->fechaPago = $fechaPago; }
    public function setApprover($approver){ $this->approver = $approver; }
    public function setRequestedBy($requestedBy){ $this->requestedBy = $requestedBy; }
    public function setDetalles($detalles){ $this->detalles = $detalles; }

    //getters
    public function getId(){ return $this->id;}
    public function getPeticionPagoId(){ return $this->peticionPagoId; } 
    public function getPagoId(){ return $this->pagoId; } 
    public function getCedula(){ return $this->cedula; }
    public function getEstado(){ return $this->estado; }
    public function getMonto(){ return $this->monto; }
    public function getFechaCreacion(){ return $this->fechaCreacion; }
    public function getFechaAprobacion(){ return $this->fechaAprobacion; }
    public function getFechaPago(){ return $this->fechaPago; }
    public function getApprover(){ return $this->approver; }
    public function getRequestedBy(){ return $this->requestedBy; }
    public function getDetalles(){ return $this->detalles; }


    public function __construct(){
        parent::__construct();

        $this->id ='';
        $this->peticionPagoId ='';
        $this->pagoId =-1;
        $this->cedula ='';
        $this->estado ='';
        $this->monto ='';
        $this->fechaCreacion ='';
        $this->fechaAprobacion ='';
        $this->fechaPago ='';
        $this->approver ='';
        $this->requestedBy ='';
        $this->detalles ='';


    }

    //recordar si las queries dan errores extranos de que el count de parametros es incorrecto, es posible que haya que inicializar 
    //las variables en el constructor
    public function save(){
        try{


            $query= $this->prepare("INSERT INTO prestamos(peticion_pago_id,
                                                pago_id,
                                                cedula,
                                                estado,
                                                monto,
                                                fecha_aprobacion,
                                                fecha_pago,
                                                approver,
                                                requestedby,
                                                detalles) 
                                        VALUES (:peticionPagoId,
                                                :pagoId,
                                                :cedula,
                                                :estado,
                                                :monto,
                                                :fechaAprobacion,
                                                :fechaPago,
                                                :approver,
                                                :requestedBy,
                                                :detalles)");


            $query->execute([
                'peticionPagoId' => $this->peticionPagoId,
                'pagoId' => $this->pagoId,
                'cedula' => $this->cedula,
                'estado' => $this->estado, 
                'monto' => $this->monto, 
                'fechaAprobacion' => $this->fechaAprobacion,
                'fechaPago' => $this->fechaPago,
                'approver' => $this->approver,
                'requestedBy' => $this->requestedBy,
                'detalles' => $this->detalles

            ]);

            //si devuelve resultado de una fila insertada
            if($query->rowCount()) return true;

            //sino
            return false;
        }catch(PDOException $e){
            error_log("PrestamosModel::save: " . $e);
            return false;
        }
    }

    //varias filas retornadas
    //crea varios objetos pago
    public function getAll(){
        $items = [];//arreglo de objetos de tipo pago

        try{
            $query = $this->query('SELECT * FROM prestamos');

            //mientras que exista un objeto no null
            while($p = $query->fetch(PDO::FETCH_ASSOC)){
                $item = new PrestamosModel();
                //rellena objeto con informacion
                $item->from($p); 
                
                array_push($items, $item);
            }

            return $items;

        }catch(PDOException $e){
            error_log("PrestamosModel::getAll: " . $e);
            echo $e;
        }
    }


    //getAllPrestamosPorPeticion
    //varias filas retornadas
    //crea varios objetos pago
    public function getAllPrestamosPorPeticion($idPeticionPago){
        $items = [];//arreglo de objetos de tipo pago

        try{
            $query = $this->prepare('SELECT * FROM prestamos WHERE peticion_pago_id = :idPeticionPago');


            $query->execute([ 'idPeticionPago' => $idPeticionPago]);

            //mientras que exista un objeto no null
            while($p = $query->fetch(PDO::FETCH_ASSOC)){
                $item = new PrestamosModel();
                //rellena objeto con informacion
                $item->from($p); 
                
                array_push($items, $item);
            }

            return $items;

        }catch(PDOException $e){
            error_log("PrestamosModel::getAllPrestamosPorPeticion: " . $e);
            echo $e;
        }
    }

    
   
    public function get($id){
        try{
            $query = $this->prepare('SELECT * FROM prestamos WHERE id = :id');
            $query->execute([ 'id' => $id]);
            $pago = $query->fetch(PDO::FETCH_ASSOC);

            //relleana objeto con la informacio mandadad en el objeto pago
            $this->from($pago);

            return $this;
        }catch(PDOException $e){
            error_log("PrestamosModel::get: " . $e);
            return false;
        }
    }

    

    public function delete($id){
        try{
            $query = $this->prepare('DELETE FROM prestamos WHERE id = :id');
            $query->execute([ 'id' => $id]);
            return true;
        }catch(PDOException $e){
            //echo $e;
            error_log("PrestamosModel::delete: " . $e);
            return false;
        }
    }


    public function update(){
        try{
            $query = $this->prepare('UPDATE prestamos SET
                                    peticion_pago_id = :peticionPagoId,
                                    pago_id = :pagoId,  
                                    cedula = :cedula,
                                    estado = :estado, 
                                    monto = :monto,  
                                    fecha_aprobacion = :fechaAprobacion,  
                                    fecha_pago = :fechaPago, 
                                    approver = :approver,
                                    requestedby = :requestedBy,
                                    detalles = :detalles
                                    WHERE id = :id');

            $query->execute([
                'id' => $this->id,
                'peticionPagoId' => $this->peticionPagoId,
                'pagoId' => $this->pagoId,
                'cedula' => $this->cedula,
                'estado' => $this->estado, 
                'monto' => $this->monto, 
                'fechaAprobacion' => $this->fechaAprobacion,
                'fechaPago' => $this->fechaPago,
                'approver' => $this->approver,
                'requestedBy' => $this->requestedBy,
                'detalles' => $this->detalles
            ]);
            return true;
        }catch(PDOException $e){
            //echo $e;
            error_log("PrestamosModel::update: " . $e);
            return false;
        }
    }


    public function from($array){
        $this->id = $array['id'];
        $this->peticionPagoId = $array['peticion_pago_id'];
        $this->pagoId = $array['pago_id'];
        $this->cedula = $array['cedula'];
        $this->estado = $array['estado'];
        $this->monto = $array['monto'];
        $this->fechaCreacion = $array['fecha_creacion'];
        $this->fechaAprobacion = $array['fecha_aprobacion'];
        $this->fechaPago = $array['fecha_pago'];
        $this->approver = $array['approver'];
        $this->requestedBy = $array['requestedby'];
        $this->detalles = $array['detalles'];
    }


    public function toArray(){
        $array = [];
        $array['id'] = $this->id;
        $array['peticion_pago_id'] = $this->peticionPagoId;
        $array['pago_id'] = $this->pagoId;
        $array['cedula'] = $this->cedula;
        // $array['nombre'] = $this->nombre;
        // $array['apellido1'] = $this->apellido1;
        // $array['apellido2'] = $this->apellido2;
        $array['estado'] = $this->estado;//adeudado
        $array['monto'] = $this->monto;//adeudado
        $array['fecha_creacion'] = $this->fechaCreacion;
        $array['fechaAprobacion'] = $this->fechaAprobacion;
        $array['fecha_pago'] = $this->fechaPago;
        $array['approver'] = $this->approver;
        $array['requestedby'] = $this->requestedBy;
        $array['detalles'] = $this->detalles;

        return $array;
    }



    //saca de la base de datos todos los prestamos que esten pendientes de pago
    //retorna arreglo de prestamos pendientes
    public function getPrestamosPendientesPago(){

        $items = [];//arreglo de objetos de tipo pago

        //$aprobado = 1;
        $estado = "aprobado";

        try{
            
            $query = $this->prepare('SELECT * FROM prestamos WHERE estado = :estado');

            $query->execute(['estado' => $estado]);

            //mientras que exista un objeto no null
            while($p = $query->fetch(PDO::FETCH_ASSOC)){
                $item = new PrestamosModel();
                //rellena objeto con informacion
                $item->from($p); 
                
                array_push($items, $item);
            }

            return $items;

        }catch(PDOException $e){
            error_log("PrestamosModel::getPrestamosPendientesPago: " . $e);

            return false;
        }
    }


    //saca de la base de datos todos los prestamos que esten pendientes de aprobacion, osea en estado open
    //retorna arreglo de prestamos pendientes aprobacion
    public function getPrestamosPendientesAprobacion(){

        $items = [];//arreglo de objetos de tipo pago

    
        $estado = "open";

        try{
            
            $query = $this->prepare('SELECT * FROM prestamos WHERE estado = :estado');

            $query->execute(['estado' => $estado]);

            //mientras que exista un objeto no null
            while($p = $query->fetch(PDO::FETCH_ASSOC)){
                $item = new PrestamosModel();
                //rellena objeto con informacion
                $item->from($p); 
                
                array_push($items, $item);
            }

            return $items;

        }catch(PDOException $e){
            error_log("PrestamosModel::getPrestamosPendientesPago: " . $e);

            return false;
        }
    }


    //devuelve todos los prestamos que tengan el id de la peticionPago dada.
    //todos los prestamos que esten con el estado de open, osea esperando a que sean aprobados
    public function getAllByPeticionPagoId($idPeticionPago){
        $items = [];//listado de pagos
        $estado = "open";

        try{
            $query = $this->prepare('SELECT * FROM prestamos 
                                    WHERE peticion_pago_id = :idPeticionPago
                                    AND estado = :estado' );
            $query->execute([ "idPeticionPago" => $idPeticionPago,
                            "estado" => $estado]);

            while($p = $query->fetch(PDO::FETCH_ASSOC)){
                $item = new PrestamosModel();
                $item->from($p); 
                
                array_push($items, $item);
            }

            return $items;

        }catch(PDOException $e){
            error_log("PrestamosModel::getAllByPeticionPagoId: " . $e);

            return [];
        }
    }




    // *********** funciones que se pueden mover a common *****************


    //Lista todos los pagos hechos a un usuario
    public function getAllByUserId($cedula){
        $items = [];//listado de pagos

        try{
            $query = $this->prepare('SELECT * FROM prestamos WHERE cedula = :cedula');
            $query->execute([ "cedula" => $cedula]);

            while($p = $query->fetch(PDO::FETCH_ASSOC)){
                $item = new PrestamosModel();
                $item->from($p); 
                
                array_push($items, $item);
            }

            return $items;

        }catch(PDOException $e){
            error_log("PrestamosModel::getAllByUserId: " . $e);
            return [];

        }
    }









}

?>