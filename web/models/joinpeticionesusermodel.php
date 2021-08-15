<?php

class JoinPeticionesUserModel extends Model {

    private $peticionId;
    private $estado;
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
        try{
          //regresa la union de donde la peticion_pago_id es igual al id de la tabla peticiones_pago con el usuario dado $userId
          
            $query = $this->query('SELECT 
                        p2.id as id_peticion, 
                        p2.cedula as cedula_contratista, 
                        u.nombre as nombre, 
                        u.apellido1 as apellido1,
                        u.apellido2 as apellido2,
                        p2.nombre as descripcion,  
                        p2.monto as monto,  
                        p2.fecha_creacion as fechaCreacion,
                        p2.estado as estado,
                        p2.detalles as detalles
                    FROM peticiones_pago AS p2
                    INNER JOIN users AS u ON p2.cedula = u.cedula       
                    ORDER BY p2.id');

            
            //WHERE p.peticion_pago_id = p2.id 
            //$query->execute(['']);

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
                $query = $this->query('SELECT 
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
                        WHERE p.estado_pago = :estado
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
        $this->pagoId = $array['id_peticion'];
        $this->cedula = $array['cedula_contratista'];
        $this->nombre = $array['nombre'];
        $this->apellido1 = $array['apellido1'];
        $this->apellido2 = $array['apellido2'];
        $this->descripcion = $array['descripcion'];
        $this->monto = $array['monto']; 
        $this->fechaCreacion = $array['fechaCreacion'];
        //$this->fechaPago = $array['fechaPago'];
        $this->estado = $array['estado'];
        $this->detalles = $array['detalles'];
    }  


    //
    public function toArray(){
        $array = [];
        $array['id_pago'] = $this->pagoId;
        $array['estado'] = $this->estadoPago;
        $array['cedula_empleado'] = $this->cedula;
        $array['nombre'] = $this->nombre;
        $array['apellido'] = $this->apellido;
        $array['adeudado'] = $this->amount;//adeudado
        $array['planilla'] = $this->peticionPagoId;
        $array['fecha_creacion'] = $this->fechaCreacion;
        $array['fecha_pago'] = $this->fechaPago;
        $array['detalles'] = $this->detalles;

        return $array;
    }


    


    public function getPagoId(){return $this->pagoId;}
    public function getCedula(){return $this->cedula;}
    public function getNombre(){return $this->nombre;}
    public function getApellido(){return $this->apellido;}
    public function getAmount(){return $this->amount;}
    public function getPeticionPagoId(){return $this->peticionPagoId;}
    public function getFechaCreacion(){return $this->fechaCreacion;}
    public function getFechaPago(){return $this->fechaPago;}
    public function getDetalles(){return $this->detalles;}

    
}

?>