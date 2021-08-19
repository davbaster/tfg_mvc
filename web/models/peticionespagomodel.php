<?php

class PeticionesPagoModel extends Model implements IModel {


    private $id; //id de peticionPago
    private $nombre; //nombre del significativo para el usuario para que identifique la planilla.
    private $cedula; //usuario con rol de contratista, usuario que creo la peticion de pago
    private $fechaCreacion;//fecha en que se crea la peticion de pago
    private $idContrato; //TODO se va a usar si se crea modulo contratos
    private $monto;
    private $estado; //open = todavia se le puede agregar pagos
                     //pendiente = ya se mando para autorizar. No se le puede agregar mas pagos.
                     //autorizado = ya se mando para autorizar, y esta autorizado esperando pago. No se le puede agregar mas pagos,
                     //parcial?? = se han hecho algunos pagos, ya no se le pueden meter mas pagos.
                     //pagado = todos los pagos fueron hechos. Falta fecha pagada? 
    // private $aprobado; //true or false
    
    private $pagos;//array que almacena los ids de pagos hechos //TODO ponerlo en la DB, revisar si esta nomencleatura es la mejor
                   //podra ser un contador de numero de pagos a los cuales esta asignado??
                   //una llave foranea a una tabla de pagos??
                   //no necesaria, se busca en la tabla de pagos con el id de la peticion de pagos.
    
    //private $listaUsuariosPagar;//TODO lista de peticiones de pago a trabajadores que estan incluidas en la peticion de pago (planilla)
                                //cuando se aprueba la peticion de pago, todas ellas se aprueban automaticamente
    private $detalles;//horas trabajadas por usuario, detalles adicionales

    public function __construct(){
        parent::__construct();

         $this->id = ''; 
         $this->nombre = ''; 
         $this->cedula = ''; 
         $this->fechaCreacion = '';
         $this->idContrato = ''; 
         $this->monto = '';
         $this->estado = ''; 
        //  $this->estadoPago = "";
         $this->detalles = "";
    }


    public function save(){
        //LOG: agregue variable nombre al insert INTO
        try{
            $query = $this->prepare('INSERT INTO peticiones_pago (nombre, cedula, id_contrato, monto, estado, detalles) 
                                    VALUES(:nombre,:cedula,  :idContrato, :monto, :estado, :detalles)');
            $query->execute([
                // 'id' => $this->id,
                'nombre' => $this->nombre, 
                'cedula' => $this->cedula, 
                // 'fechaCreacion' => $this->fechaCreacion,
                'idContrato' => $this->idContrato,
                'monto' => $this->monto,
                // 'estadoPago' => $this->estadoPago,
                'estado' => $this->estado,
                'detalles' => $this->detalles
            ]);
            if($query->rowCount()) return true;

            return false;
        }catch(PDOException $e){
            error_log("PeticionesPagos::save() " . $e );
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
        //LOG: agregue variable nombre
        try{
            $query = $this->db->connect()->prepare('UPDATE peticiones_pago SET 
                                                    nombre = :nombre,
                                                    cedula = :cedula, 
                                                    fecha_creacion = :fechaCreacion,  
                                                    id_contrato = :idContrato, 
                                                    monto = :monto, 
                                                    estado = :estado,  
                                                    detalles = :detalles WHERE id = :id');
            $query->execute([
                'id' => $this->id,
                'nombre' => $this->nombre,
                'cedula' => $this->cedula, 
                'fechaCreacion' => $this->fechaCreacion,
                'idContrato' => $this->idContrato,
                'monto' => $this->monto,
                'estado' => $this->estado,
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
        $this->setNombre           ($array['nombre'] ) ;
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

        $estado = "pendiente";//open, pendiente, aprobado, denegado, (pagado??)
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

    //
    //saca de la base de datos todos las peticiones de pagos que esten pendientes de pago
    //retorna arreglo de peticiones de pago pendientes
    public function getPeticionesPendientesPago(){

        $items = [];//arreglo de objetos de tipo pago

        $estado = "pendiente";//open, aprobado, denegado
        //$estadoPago = "pendiente"; //pending, pagado

        try{
            
            //$query = $this->prepare('SELECT * FROM peticiones_pago WHERE estado = :estado AND estado_pago = :estadopago');
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


    //Devuelve las peticionesPago en estado OPEN 
    //osea planillas que no se han enviado para aprobacion
    public function getPeticionesNoEnviadas(){

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


  


     //getExpensesJSON
     function getPagosJSON(){

        header('Content-Type: application/json');

        $res = [];
        $peticionesPagoIds     = $this->getPeticionesPagoIds();//esta en esta misma clase, linea 67
        $peticionPagoNames  = $this->getPeticionPagoList(); //linea 105
        $categoryColors = $this->getCategoryColorList();

        //acomodando informacion para google chart
        array_unshift($peticionPagoNames, 'mes');
        array_unshift($categoryColors, 'categorias');
        /* array_unshift($categoryNames, 'categorias');
        array_unshift($categoryColors, NULL); */

        $months = $this->getDateList();

        //itera entre los ids y los meses para acomodar los pagos
        //crea matriz
        for($i = 0; $i < count($months); $i++){
            $item = array($months[$i]);
            for($j = 0; $j < count($peticionesPagoIds); $j++){
                $total = $this->getTotalByMonthAndCategory( $months[$i], $peticionesPagoIds[$j]);
                array_push( $item, $total );
            }   
            array_push($res, $item);
        }
        
        array_unshift($res, $peticionPagoNames);
        array_unshift($res, $categoryColors);
        
        echo json_encode($res);
    }


    



    //setters

    public function setId($id){$this->id = $id;}
    public function setNombre($nombre){$this->nombre = $nombre;}
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
    public function getNombre(){ return $this->nombre;}
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