<?php

class JoinPrestamosUserModel extends Model {

    private $id;
    private $peticionPagoId;
    private $pagoId;
    private $cedula; 
    private $estado;//open->pendiente->autorizada->pagada
    private $monto;
    private $fechaCreacion;//fecha en que se creo el pago
    private $fechaAprobacion;
    private $fechaPago;
    private $detalles;



    private $nombre;
    private $apellido1; 
    private $apellido2; 


    public function __construct()
    {
        parent::__construct();
    }


    public function get($id){
        $items = [];

        
        //$estadoPagado = "pagado";

        try{
          //regresa la union de donde la peticion_pago_id es igual al id de la tabla peticiones_pago con el usuario dado $userId
           //WHERE p2.fecha_pago as fechaPago,
            $query = $this->prepare('SELECT 
                        pr.id as id_prestamo,
                        pr.peticion_pago_id as peticion_pago_id,
                        pr.pago_id as id_pago, 
                        pr.cedula as cedula,
                        pr.estado as estado,  
                        pr.monto as monto,
                        pr.fecha_creacion as fecha_creacion,
                        pr.fecha_aprobacion as fecha_aprobacion,
                        pr.fecha_pago as fechaPago, 
                        pr.approver as aprobadoPor,
                        pr.requestedBy as pedidoPor,
                        pr.detalles as detalles,  
                        u.nombre as nombre, 
                        u.apellido1 as apellido1, 
                        u.apellido2 as apellido2

                    FROM prestamos AS pr
                    INNER JOIN users AS u ON pr.cedula = u.cedula      
                    WHERE pr.id = :id');

            
            //$query->execute(['']);
            $query->execute(["id" => $id]);

            //$p es un arreglo que guarda las filas del query anterior, filas de un join
            $p = $query->fetch(PDO::FETCH_ASSOC);
                $item = new JoinPrestamosUserModel();
                $item->from($p);//va rellenando el objeto del tipo JoinPrestamosUserModel con la info de las filas guardadas en $p


            return $item;//devuelve un objeto JoinPagosPeticionesModel

        }catch(PDOException $e){
            error_log('JOINPRESTAMOSUSERMODEL::get => ' . $e);
            echo $e;
        }
    }


    //Lista peticiones pendientes, autorizadas. Todos los estados
    //lISTA TODOS LOS PRESTAMOS PENDIENTES DE AUTORIZAR para 
    public function getAllPrestamosPendientes(){
        $items = [];

        $estado = "pendiente";

        try{
          //regresa la union de donde la peticion_pago_id es igual al id de la tabla peticiones_pago con el usuario dado $userId
           //WHERE p2.fecha_pago as fechaPago,
            $query = $this->prepare('SELECT 
                        pr.id as id_prestamo,
                        pr.peticion_pago_id as peticion_pago_id ,
                        pr.pago_id as id_pago, 
                        pr.cedula as cedula,
                        pr.estado as estado,  
                        pr.monto as monto,
                        pr.fecha_creacion as fecha_creacion,
                        pr.fecha_aprobacion as fecha_aprobacion,
                        pr.fecha_pago as fechaPago, 
                        pr.approver as aprobadoPor,
                        pr.requestedBy as pedidoPor,
                        pr.detalles as detalles,  
                        u.nombre as nombre, 
                        u.apellido1 as apellido1, 
                        u.apellido2 as apellido2
                        
                        
                        
                    FROM prestamos AS pr
                    INNER JOIN users AS u ON pr.cedula = u.cedula      
                    WHERE pr.estado = :estado
                    ORDER BY pr.cedula');

            
            
            $query->execute(["estado" => $estado]);

            //$p es un arreglo que guarda las filas del query anterior, filas de un join
            while($p = $query->fetch(PDO::FETCH_ASSOC)){
                $item = new JoinPrestamosUserModel();
                $item->from($p);//va rellenando el objeto del tipo JoinPagosPeticionesModel con la info de las filas guardadas en $p
                array_push($items, $item);//va rellenando el objeto del tipo JoinPagosPeticionesModel con la info de las filas guardadas en $p
            }

            return $items;//devuelve un arreglo de objetos de JoinPagosPeticionesModel

        }catch(PDOException $e){
            error_log('JOINPRESTAMOSUSER_MODEL::getAllPrestamosPendientes => ' . $e);
            echo $e;
        }
    }





    //Lista peticiones pendientes, autorizadas. Todos los estados
    //lISTA TODOS LOS PRESTAMOS PENDIENTES DE AUTORIZAR para 
    public function getAllPrestamosAutorizados(){
        $items = [];

        $estado = "autorizado";

        try{
          //regresa la union de donde la peticion_pago_id es igual al id de la tabla peticiones_pago con el usuario dado $userId
           //WHERE p2.fecha_pago as fechaPago,
            $query = $this->prepare('SELECT 
                        pr.id as id_prestamo,
                        pr.peticion_pago_id as peticion_pago_id ,
                        pr.pago_id as id_pago, 
                        pr.cedula as cedula,
                        pr.estado as estado,  
                        pr.monto as monto,
                        pr.fecha_creacion as fecha_creacion,
                        pr.fecha_aprobacion as fecha_aprobacion,
                        pr.fecha_pago as fechaPago, 
                        pr.approver as aprobadoPor,
                        pr.requestedBy as pedidoPor,
                        pr.detalles as detalles,  
                        u.nombre as nombre, 
                        u.apellido1 as apellido1, 
                        u.apellido2 as apellido2
                        
                        
                        
                    FROM prestamos AS pr
                    INNER JOIN users AS u ON pr.cedula = u.cedula      
                    WHERE pr.estado = :estado
                    ORDER BY pr.cedula');

            
            
            $query->execute(["estado" => $estado]);

            //$p es un arreglo que guarda las filas del query anterior, filas de un join
            while($p = $query->fetch(PDO::FETCH_ASSOC)){
                $item = new JoinPrestamosUserModel();
                $item->from($p);//va rellenando el objeto del tipo JoinPagosPeticionesModel con la info de las filas guardadas en $p
                array_push($items, $item);//va rellenando el objeto del tipo JoinPagosPeticionesModel con la info de las filas guardadas en $p
            }

            return $items;//devuelve un arreglo de objetos de JoinPagosPeticionesModel

        }catch(PDOException $e){
            error_log('JOINPRESTAMOSUSER_MODEL::getAllPrestamosPendientes => ' . $e);
            echo $e;
        }
    }



    
    //lISTA TODOS LOS PRESTAMOS RECHAZADOS por un supervisor 
    public function getAllPrestamosRechazados(){
        $items = [];

        $estado = "rechazado";

        try{
          //regresa la union de donde la peticion_pago_id es igual al id de la tabla peticiones_pago con el usuario dado $userId
           //WHERE p2.fecha_pago as fechaPago,
            $query = $this->prepare('SELECT 
                        pr.id as id_prestamo,
                        pr.peticion_pago_id as peticion_pago_id ,
                        pr.pago_id as id_pago, 
                        pr.cedula as cedula,
                        pr.estado as estado,  
                        pr.monto as monto,
                        pr.fecha_creacion as fecha_creacion,
                        pr.fecha_aprobacion as fecha_aprobacion,
                        pr.fecha_pago as fechaPago, 
                        pr.approver as aprobadoPor,
                        pr.requestedBy as pedidoPor,
                        pr.detalles as detalles,  
                        u.nombre as nombre, 
                        u.apellido1 as apellido1, 
                        u.apellido2 as apellido2
                        
                        
                        
                    FROM prestamos AS pr
                    INNER JOIN users AS u ON pr.cedula = u.cedula      
                    WHERE pr.estado = :estado
                    ORDER BY pr.cedula');

            
            
            $query->execute(["estado" => $estado]);

            //$p es un arreglo que guarda las filas del query anterior, filas de un join
            while($p = $query->fetch(PDO::FETCH_ASSOC)){
                $item = new JoinPrestamosUserModel();
                $item->from($p);//va rellenando el objeto del tipo JoinPagosPeticionesModel con la info de las filas guardadas en $p
                array_push($items, $item);//va rellenando el objeto del tipo JoinPagosPeticionesModel con la info de las filas guardadas en $p
            }

            return $items;//devuelve un arreglo de objetos de JoinPagosPeticionesModel

        }catch(PDOException $e){
            error_log('JOINPRESTAMOSUSER_MODEL::getAllPrestamosPendientes => ' . $e);
            echo $e;
        }
    }


       



        



    
   


    

    //
    public function from($array){

        $this->id = $array['id_prestamo'];
        $this->peticionPagoId = $array['peticion_pago_id'];
        $this->pagoId = $array['id_pago'];
        $this->cedula = $array['cedula'];
        $this->estado = $array['estado'];
        $this->monto = $array['monto'];
        $this->fechaCreacion = $array['fecha_creacion'];
        $this->fechaAprobacion = $array['fecha_aprobacion'];
        $this->fechaPago = $array['fecha_pago'];
        $this->approver = $array['approver'];
        $this->requestedBy = $array['requestedby'];
        $this->detalles = $array['detalles'];

        $this->nombre = $array['nombre'];
        $this->apellido1 =  $array['apellido1'];
        $this->apellido2  =  $array['apellido2'];


    
    }  


    //
    public function toArray(){
        $array = [];

        $array = [];
        $array['id_prestamo'] = $this->id;
        $array['peticion_pago_id'] = $this->peticionPagoId;
        $array['pago_id'] = $this->pagoId;
        $array['cedula'] = $this->cedula;
        $array['estado'] = $this->estado;//adeudado
        $array['monto'] = $this->monto;//adeudado
        $array['fecha_creacion'] = $this->fechaCreacion;
        $array['fechaAprobacion'] = $this->fechaAprobacion;
        $array['fecha_pago'] = $this->fechaPago;
        $array['approver'] = $this->approver;
        $array['requestedby'] = $this->requestedBy;
        $array['detalles'] = $this->detalles;
        $array['nombre'] = $this->nombre;
        $array['apellido1'] = $this->apellido1;
        $array['apellido2'] = $this->apellido2;

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