<?php

class JoinPagosPeticionesModel extends Model {

    private $pagoId;
    private $estado;//estado del pago
    private $cedula;
    private $nombre;
    private $apellido1;
    private $apellido2;  
    private $amount;
    private $peticionPagoId; //planillaID
    private $fechaPago;
    private $fechaCreacion;//fecha en que se creo el pago
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
                        p.id as id_pago, 
                        p.estado_pago as estado,
                        p.cedula as cedula_empleado, 
                        u.nombre as nombre, 
                        u.apellido1 as apellido1,
                        u.apellido1 as apellido2, 
                        p.amount as adeudado, 
                        p.peticion_pago_id as planilla_id, 
                        p.fecha_creacion as fechaCreacion,
                        p.fecha_pago as fechaPago,
                        p.detalles as detalles
                    FROM pagos AS p
                    INNER JOIN users AS u ON p.cedula = u.cedula
                    INNER JOIN peticiones_pago AS p2 ON p.peticion_pago_id  = p2.id        
                    WHERE p.peticion_pago_id = p2.id 
                    ORDER BY p.cedula');

            
            //$query->execute(['']);

            //$p es un arreglo que guarda las filas del query anterior, filas de un join
            while($p = $query->fetch(PDO::FETCH_ASSOC)){
                $item = new JoinPagosPeticionesModel();
                $item->from($p);//va rellenando el objeto del tipo JoinPagosPeticionesModel con la info de las filas guardadas en $p
                array_push($items, $item);//va rellenando el objeto del tipo JoinPagosPeticionesModel con la info de las filas guardadas en $p
            }

            return $items;//devuelve un arreglo de objetos de JoinPagosPeticionesModel

        }catch(PDOException $e){
            error_log('JOINPAGOSPETICIONESMODEL::getAllPeticiones => ' . $e);
            echo $e;
        }
    }


 
    //devuelve los pagos pendientes de pagar
    public function getAllPagos(){
        $items = [];
        //$estado = "pendiente";
        try{
                //regresa la union de donde la peticion_pago_id es igual al id de la tabla peticiones_pago con el usuario dado $userId
                //p2.cedula as contratista,//TODO agregar luego por si queremos poner el nombre del contratista
                $query = $this->query('SELECT 
                p.id as id_pago,
                p.estado_pago as estado, 
                p.cedula as cedula_empleado, 
                u.nombre as nombre, 
                u.apellido1 as apellido1,
                u.apellido1 as apellido2, 
                p.amount as adeudado, 
                p.peticion_pago_id as planilla_id, 
                p.estado_pago as estado,
                p.fecha_creacion as fechaCreacion,
                p.fecha_pago as fechaPago,
                p.detalles as detalles
            FROM pagos AS p
            INNER JOIN users AS u ON p.cedula = u.cedula
            INNER JOIN peticiones_pago AS p2 ON p.peticion_pago_id  = p2.id        
            WHERE p.peticion_pago_id = p2.id 
            ORDER BY p.cedula');


            //$query->execute(['']);

            //$p es un arreglo que guarda las filas del query anterior, filas de un join
            while($p = $query->fetch(PDO::FETCH_ASSOC)){
            $item = new JoinPagosPeticionesModel();
            $item->from($p);//va rellenando el objeto del tipo JoinPagosPeticionesModel con la info de las filas guardadas en $p
            array_push($items, $item);//va rellenando el objeto del tipo JoinPagosPeticionesModel con la info de las filas guardadas en $p
            }

            return $items;//devuelve un arreglo de objetos de JoinPagosPeticionesModel

        }catch(PDOException $e){
            echo $e;
        }
    }

    //devuelve todos los pagos con estado OPEN dado un ID de peticionPago
    public function getAllPagosOpen($peticionPagoId){
        $items = [];
        $estado = "open";
        try{
          //regresa la union de donde la peticion_pago_id es igual al id de la tabla peticiones_pago con el usuario dado $userId
            $query = $this->prepare('SELECT p.id as id_pago, 
                             p.estado_pago as estado,
                             p.cedula as cedula_empleado, 
                             u.nombre as nombre, 
                             u.apellido1 as apellido1,
                             u.apellido1 as apellido2, 
                             p.amount as adeudado, 
                             p.peticion_pago_id as planilla_id, 
                             p.fecha_creacion as fechaCreacion,
                             p.fecha_pago as fechaPago,
                             p.detalles as detalles
                    FROM pagos AS p
                    INNER JOIN users AS u ON p.cedula = u.cedula
                    INNER JOIN peticiones_pago AS p2 ON p.peticion_pago_id  = p2.id
                    WHERE p.estado_pago = :estado
                    AND p.peticion_pago_id = :peticionPagoId  
                    ORDER BY p.cedula');
            
            
            $query->execute(["estado" => $estado,
                             "peticionPagoId" => $peticionPagoId]);

            //$p es un arreglo que guarda las filas del query anterior, filas de un join
            while($p = $query->fetch(PDO::FETCH_ASSOC)){
                $item = new JoinPagosPeticionesModel();
                $item->from($p);//va rellenando el objeto del tipo JoinPagosPeticionesModel con la info de las filas guardadas en $p
                array_push($items, $item);
            }

            return $items;//devuelve un arreglo de objetos de JoinPagosPeticionesModel

        }catch(PDOException $e){
            echo $e;
        }
    }


    //devuelve todos los pagos que estan relaciones con un peticionPagoID
    public function getAllPagosPorPeticion($peticionPagoId){
        $items = [];
        try{
          //regresa la union de donde la peticion_pago_id es igual al id de la tabla peticiones_pago con el usuario dado $userId
          //$query = $this->prepare('SELECT expenses.id as expense_id, title, category_id, amount, date, id_user, categories.id, name, color  FROM expenses INNER JOIN categories WHERE expenses.category_id = categories.id AND expenses.id_user = :userid ORDER BY date');
            //$query = $this->prepare('SELECT pagos.id as pago_id, estado_pago, peticion_pago_id, amount, date, cedula, peticiones_pago.id FROM pagos INNER JOIN peticiones_pago WHERE pagos.peticion_pago_id = peticiones_pago.id AND pagos.id_user = :userId ORDER BY date');
            $query = $this->prepare('SELECT 
                        p.id as id_pago, 
                        p.estado_pago as estado,
                        p.cedula as cedula_empleado, 
                        u.nombre as nombre, 
                        u.apellido1 as apellido1,
                        u.apellido1 as apellido2, 
                        p.amount as adeudado, 
                        p.peticion_pago_id as planilla_id, 
                        p.fecha_creacion as fechaCreacion,
                        p.fecha_pago as fechaPago,
                        p.detalles as detalles
                    FROM pagos AS p
                    INNER JOIN users AS u ON p.cedula = u.cedula
                    INNER JOIN peticiones_pago AS p2 ON p.peticion_pago_id  = p2.id        
                    WHERE p.peticion_pago_id = :peticionPagoId 
                    ORDER BY p.cedula');

            
            $query->execute(["peticionPagoId" => $peticionPagoId]);

            //$p es un arreglo que guarda las filas del query anterior, filas de un join
            while($p = $query->fetch(PDO::FETCH_ASSOC)){
                $item = new JoinPagosPeticionesModel();
                $item->from($p);//va rellenando el objeto del tipo JoinPagosPeticionesModel con la info de las filas guardadas en $p
                array_push($items, $item);//va rellenando el objeto del tipo JoinPagosPeticionesModel con la info de las filas guardadas en $p
            }

            return $items;//devuelve un arreglo de objetos de JoinPagosPeticionesModel

        }catch(PDOException $e){
            echo $e;
        }
    }



    //cual pago, a quien se le paga, de cual planilla,      y cual contratista,     fecha de la peticion de pago,  estado del pago.
    //pagos.id, pagos.cedula,        pagos.peticion_pago_id, peticiones_pago.cedula, peticiones_pago.date,         pagos.estado_pago
    public function getAllPagosPorUsuario($cedula){
        $items = [];
        try{
          //regresa la union de donde la peticion_pago_id es igual al id de la tabla peticiones_pago con el usuario dado $userId
          //$query = $this->prepare('SELECT expenses.id as expense_id, title, category_id, amount, date, id_user, categories.id, name, color  FROM expenses INNER JOIN categories WHERE expenses.category_id = categories.id AND expenses.id_user = :userid ORDER BY date');
            //$query = $this->prepare('SELECT pagos.id as pago_id, estado_pago, peticion_pago_id, amount, date, cedula, peticiones_pago.id FROM pagos INNER JOIN peticiones_pago WHERE pagos.peticion_pago_id = peticiones_pago.id AND pagos.id_user = :userId ORDER BY date');
            $query = 'SELECT 
                        p.id as id_pago, 
                        p.estado_pago as estado,
                        p.cedula as cedula_empleado, 
                        u.nombre as nombre, 
                        u.apellido1 as apellido1,
                        u.apellido1 as apellido2, 
                        p.amount as adeudado, 
                        p.peticion_pago_id as planilla_id, 
                        p.fecha_creacion as fechaCreacion,
                        p.fecha_pago as fechaPago,
                        p.detalles as detalles
                    FROM pagos AS p
                    INNER JOIN users AS u ON p.cedula = u.cedula
                    INNER JOIN peticiones_pago AS p2 ON p.peticion_pago_id  = p2.id        
                    WHERE p.peticion_pago_id = p2.id AND p.cedula = :cedula 
                    ORDER BY p.cedula';

            
            $query->execute(["userId" => $cedula]);

            //$p es un arreglo que guarda las filas del query anterior, filas de un join
            while($p = $query->fetch(PDO::FETCH_ASSOC)){
                $item = new JoinPagosPeticionesModel();
                $item->from($p);//va rellenando el objeto del tipo JoinPagosPeticionesModel con la info de las filas guardadas en $p
                array_push($items, $item);//va rellenando el objeto del tipo JoinPagosPeticionesModel con la info de las filas guardadas en $p
            }

            return $items;//devuelve un arreglo de objetos de JoinPagosPeticionesModel

        }catch(PDOException $e){
            echo $e;
        }
    }

    //devuelve todos los pagos pagados dado un usuario
    public function getAllPagosHechos($cedula){
        $items = [];
        $estado = "pagado";
        try{
          //regresa la union de donde la peticion_pago_id es igual al id de la tabla peticiones_pago con el usuario dado $userId
          //$query = $this->prepare('SELECT expenses.id as expense_id, title, category_id, amount, date, id_user, categories.id, name, color  FROM expenses INNER JOIN categories WHERE expenses.category_id = categories.id AND expenses.id_user = :userid ORDER BY date');
            //$query = $this->prepare('SELECT pagos.id as pago_id, estado_pago, peticion_pago_id, amount, date, cedula, peticiones_pago.id FROM pagos INNER JOIN peticiones_pago WHERE pagos.peticion_pago_id = peticiones_pago.id AND pagos.id_user = :userId ORDER BY date');
            $query = $this->prepare('SELECT 
                        p.id as id_pago, 
                        p.estado_pago as estado,
                        p.cedula as cedula_empleado, 
                        u.nombre as nombre, 
                        u.apellido1 as apellido1,
                        u.apellido1 as apellido2, 
                        p.amount as adeudado, 
                        p.peticion_pago_id as planilla_id, 
                        p.fecha_creacion as fechaCreacion,
                        p.fecha_pago as fechaPago,
                        p.detalles as detalles
                    FROM pagos AS p
                    INNER JOIN users AS u ON p.cedula = u.cedula
                    INNER JOIN peticiones_pago AS p2 ON p.peticion_pago_id  = p2.id        
                    WHERE p.peticion_pago_id = p2.id AND p.cedula = :cedula AND  p.estado_pago = :estado
                    ORDER BY p.cedula');

            
            $query->execute(["cedula" => $cedula,
                            "estado" => $estado]);

            //$p es un arreglo que guarda las filas del query anterior, filas de un join
            while($p = $query->fetch(PDO::FETCH_ASSOC)){
                $item = new JoinPagosPeticionesModel();
                $item->from($p);//va rellenando el objeto del tipo JoinPagosPeticionesModel con la info de las filas guardadas en $p
                array_push($items, $item);//va rellenando el objeto del tipo JoinPagosPeticionesModel con la info de las filas guardadas en $p
            }

            return $items;//devuelve un arreglo de objetos de JoinPagosPeticionesModel

        }catch(PDOException $e){
            echo $e;
        }
    }



    //devuelve los pagos pendientes de pagar
    
    public function getAllPagosPendientes(){
        $items = [];
        $estado = "pendiente";
        try{
          //regresa la union de donde la peticion_pago_id es igual al id de la tabla peticiones_pago con el usuario dado $userId
            $query = $this->prepare('SELECT p.id as id_pago,
                             p.estado_pago as estado, 
                             p.cedula as cedula_empleado, 
                             u.nombre as nombre, 
                             u.apellido1 as apellido1,
                             u.apellido1 as apellido2, 
                             p.amount as adeudado, 
                             p.peticion_pago_id as planilla_id, 
                             p.fecha_creacion as fechaCreacion,
                             p.fecha_pago as fechaPago,
                             p.detalles as detalles
                    FROM pagos AS p
                    INNER JOIN users AS u ON p.cedula = u.cedula
                    INNER JOIN peticiones_pago AS p2 ON p.peticion_pago_id  = p2.id
                    WHERE p.estado_pago = :estado      
                    ORDER BY p.cedula');
            
            
            $query->execute(["estado" => $estado]);

            //$p es un arreglo que guarda las filas del query anterior, filas de un join
            while($p = $query->fetch(PDO::FETCH_ASSOC)){
                $item = new JoinPagosPeticionesModel();
                $item->from($p);//va rellenando el objeto del tipo JoinPagosPeticionesModel con la info de las filas guardadas en $p
                array_push($items, $item);
            }

            return $items;//devuelve un arreglo de objetos de JoinPagosPeticionesModel

        }catch(PDOException $e){
            echo $e;
        }
    }


    //devuelve pagos pendientes de pago y pagados
    public function getAllPagosAutorizados(){
        $items = [];
        $estadoPendiente = "pendiente";
        $estadoPagado = "pagado";
        try{
          //regresa la union de donde la peticion_pago_id es igual al id de la tabla peticiones_pago con el usuario dado $userId
            $query = $this->prepare('SELECT p.id as id_pago,
                             p.estado_pago as estado, 
                             p.cedula as cedula_empleado, 
                             u.nombre as nombre, 
                             u.apellido1 as apellido1,
                             u.apellido1 as apellido2, 
                             p.amount as adeudado, 
                             p.peticion_pago_id as planilla_id, 
                             p.fecha_creacion as fechaCreacion,
                             p.fecha_pago as fechaPago,
                             p.detalles as detalles
                    FROM pagos AS p
                    INNER JOIN users AS u ON p.cedula = u.cedula
                    INNER JOIN peticiones_pago AS p2 ON p.peticion_pago_id  = p2.id
                    WHERE p.estado_pago = :estadoPendiente OR p.estado_pago = :estadoPagado      
                    ORDER BY p.cedula');
            
            
            $query->execute(["estadoPendiente" => $estadoPendiente,
                             "estadoPagado" => $estadoPagado]);

            //$p es un arreglo que guarda las filas del query anterior, filas de un join
            while($p = $query->fetch(PDO::FETCH_ASSOC)){
                $item = new JoinPagosPeticionesModel();
                $item->from($p);//va rellenando el objeto del tipo JoinPagosPeticionesModel con la info de las filas guardadas en $p
                array_push($items, $item);
            }

            return $items;//devuelve un arreglo de objetos de JoinPagosPeticionesModel

        }catch(PDOException $e){
            echo $e;
        }
    }



    //devuelve los ultimos $n = 5 pagos hechos
    public function getPagosRecientes($n = 2){
        $items = [];
        $estado = "pagado";
        try{
          //regresa la union de donde la peticion_pago_id es igual al id de la tabla peticiones_pago con el usuario dado $userId
            $query = $this->prepare('SELECT p.id as id_pago,
                             p.estado_pago as estado, 
                             p.cedula as cedula_empleado, 
                             u.nombre as nombre, 
                             u.apellido1 as apellido1,
                             u.apellido1 as apellido2, 
                             p.amount as adeudado, 
                             p.peticion_pago_id as planilla_id, 
                             p.fecha_creacion as fechaCreacion,
                             p.fecha_pago as fechaPago,
                             p.detalles as detalles
                    FROM pagos AS p
                    INNER JOIN users AS u ON p.cedula = u.cedula
                    INNER JOIN peticiones_pago AS p2 ON p.peticion_pago_id  = p2.id
                    WHERE p.estado_pago = :estado      
                    ORDER BY p.fecha_creacion 
                    DESC LIMIT :n ');
            
            
            $query->execute(["n" => $n,
                            "estado" => $estado]);

            //$p es un arreglo que guarda las filas del query anterior, filas de un join
            while($p = $query->fetch(PDO::FETCH_ASSOC)){
                $item = new JoinPagosPeticionesModel();
                $item->from($p);//va rellenando el objeto del tipo JoinPagosPeticionesModel con la info de las filas guardadas en $p
                array_push($items, $item);
            }

            return $items;//devuelve un arreglo de objetos de JoinPagosPeticionesModel

        }catch(PDOException $e){
            echo $e;
        }
    }




    //
    public function from($array){
        $this->pagoId = $array['id_pago'];
        $this->estado = $array['estado'];
        $this->cedula = $array['cedula_empleado'];
        $this->nombre = $array['nombre'];
        $this->apellido1 = $array['apellido1'];
        $this->apellido2 = $array['apellido2'];
        $this->amount = $array['adeudado'];
        $this->peticionPagoId = $array['planilla_id'];
        $this->fechaCreacion = $array['fechaCreacion'];
        $this->fechaPago = $array['fechaPago'];
        $this->detalles = $array['detalles'];
    }


    //utilizado para meter la info de un objeto a un array, 

    public function toArray(){
        $array = [];
        $array['id_pago'] = $this->pagoId;
        $array['estado'] = $this->estado;
        $array['cedula_empleado'] = $this->cedula;
        $array['nombre'] = $this->nombre;
        $array['apellido1'] = $this->apellido1;
        $array['apellido2'] = $this->apellido2;
        $array['adeudado'] = $this->amount;//adeudado
        $array['planilla_id'] = $this->peticionPagoId;
        $array['fecha_creacion'] = $this->fechaCreacion;
        $array['fecha_pago'] = $this->fechaPago;
        $array['detalles'] = $this->detalles;

        return $array;
    }


    


    public function getPagoId(){return $this->pagoId;}
    public function getEstado(){return $this->estado;}
    public function getCedula(){return $this->cedula;}
    public function getNombre(){return $this->nombre;}
    public function getApellido1(){return $this->apellido1;}
    public function getApellido2(){return $this->apellido2;}
    public function getAmount(){return $this->amount;}
    public function getPeticionPagoId(){return $this->peticionPagoId;}
    public function getFechaCreacion(){return $this->fechaCreacion;}
    public function getFechaPago(){return $this->fechaPago;}
    public function getDetalles(){return $this->detalles;}

    
}

?>