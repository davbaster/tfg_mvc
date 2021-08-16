<?php

class JoinPeticionesUserModel extends Model {

    private $peticionId;
    private $estado;//open->pendiente->autorizada->pagada
    private $cedula;
    private $nombre;
    private $apellido1; 
    private $apellido2; 
    private $descripcion; 
    private $monto;
    private $fechaCreacion;//fecha en que se creo el pago
    private $fechaAprobacion;
    private $fechaPago;
    private $detalles;


    public function __construct()
    {
        parent::__construct();
    }


    //Lista peticiones open, pendientes, autorizadas
    public function getAllPeticiones(){
        $items = [];

        $estadoAutorizada = "autorizada";
        $estadoPendiente = "pendiente";

        try{
          //regresa la union de donde la peticion_pago_id es igual al id de la tabla peticiones_pago con el usuario dado $userId
           //WHERE p2.fecha_pago as fechaPago,
            $query = $this->prepare('SELECT 
                        p2.id as id_planilla, 
                        p2.estado as estado, 
                        p2.cedula as cedula_contratista, 
                        u.nombre as nombre, 
                        u.apellido1 as apellido1, 
                        u.apellido2 as apellido2,
                        p2.monto as monto, 
                        p2.fecha_creacion as fechaCreacion,
                        p2.detalles as detalles
                    FROM peticiones_pago AS p2
                    INNER JOIN users AS u ON p2.cedula = u.cedula      
                    WHERE p2.estado = :estadoAutorizada
                    OR p2.estado = :estadoPendiente
                    ORDER BY p2.cedula');

            
            
            $query->execute(["estadoAutorizada" => $estadoAutorizada,
                            "estadoPendiente" => $estadoPendiente]);

            //$p es un arreglo que guarda las filas del query anterior, filas de un join
            while($p = $query->fetch(PDO::FETCH_ASSOC)){
                $item = new JoinPeticionesUserModel();
                $item->from($p);//va rellenando el objeto del tipo JoinPagosPeticionesModel con la info de las filas guardadas en $p
                array_push($items, $item);//va rellenando el objeto del tipo JoinPagosPeticionesModel con la info de las filas guardadas en $p
            }

            return $items;//devuelve un arreglo de objetos de JoinPagosPeticionesModel

        }catch(PDOException $e){
            error_log('JOINPAGOSPETICIONESMODEL::getAllPeticiones => ' . $e);
            echo $e;
        }
    }


        //Lista peticiones autorizadas a ser pagadas que tienen el estado de "pendiente" para pago
        public function getAllPeticionesAutorizadas(){
            $items = [];

            $estado = "autorizada";
            //$estadoPagado = "pagado";

            try{
              //regresa la union de donde la peticion_pago_id es igual al id de la tabla peticiones_pago con el usuario dado $userId
               //WHERE p2.fecha_pago as fechaPago,
                $query = $this->prepare('SELECT 
                            p2.id as id_planilla, 
                            p2.estado as estado, 
                            p2.cedula as cedula_contratista, 
                            u.nombre as nombre, 
                            u.apellido1 as apellido1, 
                            u.apellido2 as apellido2,
                            p2.monto as monto, 
                            p2.fecha_creacion as fechaCreacion,
                            p2.detalles as detalles
                        FROM peticiones_pago AS p2
                        INNER JOIN users AS u ON p2.cedula = u.cedula      
                        WHERE p2.estado = :estado
                        ORDER BY p2.cedula');
   
                
                //$query->execute(['']);
                $query->execute(["estado" => $estado]);
    
                //$p es un arreglo que guarda las filas del query anterior, filas de un join
                while($p = $query->fetch(PDO::FETCH_ASSOC)){
                    $item = new JoinPeticionesUserModel();
                    $item->from($p);//va rellenando el objeto del tipo JoinPagosPeticionesModel con la info de las filas guardadas en $p
                    array_push($items, $item);//va rellenando el objeto del tipo JoinPagosPeticionesModel con la info de las filas guardadas en $p
                }
    
                return $items;//devuelve un arreglo de objetos de JoinPagosPeticionesModel
    
            }catch(PDOException $e){
                error_log('JOINPAGOSPETICIONESMODEL::getAllPeticionesAutorizadas => ' . $e);
                echo $e;
            }
        }


    

    //
    public function from($array){
        $this->peticionId = $array['id_planilla'];
        $this->estado = $array['estado'];
        $this->cedula = $array['cedula_contratista'];
        $this->nombre = $array['nombre'];
        $this->apellido1 = $array['apellido1'];
        $this->apellido2 = $array['apellido2'];
        $this->monto = $array['monto']; 
        $this->fechaCreacion = $array['fechaCreacion'];
        $this->detalles = $array['detalles'];


    
    }  


    //
    public function toArray(){
        $array = [];
        $array['id_planilla'] = $this->peticionId;
        $array['estado'] = $this->estado;
        $array['cedula_contratista'] = $this->cedula;
        $array['nombre'] = $this->nombre;
        $array['apellido1'] = $this->apellido1;
        $array['apellido2'] = $this->apellido2;
        $array['monto'] = $this->monto;//adeudado
        $array['fecha_creacion'] = $this->fechaCreacion;
        //$array['fecha_pago'] = $this->fechaPago;
        $array['detalles'] = $this->detalles;

        return $array;
    }


    


    public function getPeticionPagoId(){return $this->peticionId;}
    public function getEstado(){return $this->estado;}
    public function getCedula(){return $this->cedula;}
    public function getNombre(){return $this->nombre;}
    public function getApellido1(){return $this->apellido1;}
    public function getApellido2(){return $this->apellido2;}
    public function getMonto(){return $this->monto;}
    
    public function getFechaCreacion(){return $this->fechaCreacion;}
    //public function getFechaPago(){return $this->fechaPago;}
    public function getDetalles(){return $this->detalles;}

    
}

?>