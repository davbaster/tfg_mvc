<?php

class JoinPagosPeticionesModel extends Model {

    private $pagoId;
    private $cedula;
    private $nombre;
    private $apellido; 
    private $amount;
    private $peticionPagoId; //planillaID
    private $date;


    public function __construct()
    {
        parent::__construct();
    }


    //
    // public function getAll($userId){
    //     $items = [];
    //     try{
    //       //regresa la union de donde la peticion_pago_id es igual al id de la tabla peticiones_pago con el usuario dado $userId
    //       //$query = $this->prepare('SELECT expenses.id as expense_id, title, category_id, amount, date, id_user, categories.id, name, color  FROM expenses INNER JOIN categories WHERE expenses.category_id = categories.id AND expenses.id_user = :userid ORDER BY date');
    //         $query = $this->prepare('SELECT pagos.id as pago_id, title, peticion_pago_id, amount, date, id_user, peticiones_pago.id, name, color  FROM pagos INNER JOIN peticiones_pago WHERE pagos.peticion_pago_id = peticiones_pago.id AND pagos.id_user = :userId ORDER BY date');
    //         $query->execute(["userId" => $userId]);

    //         //$p es un arreglo que guarda las filas del query anterior, filas de un join
    //         while($p = $query->fetch(PDO::FETCH_ASSOC)){
    //             $item = new JoinPagosPeticionesModel();
    //             $item->from($p);//va rellenando el objeto del tipo JoinPagosPeticionesModel con la info de las filas guardadas en $p
    //             array_push($items, $item);
    //         }

    //         return $items;//devuelve un arreglo de objetos de JoinPagosPeticionesModel

    //     }catch(PDOException $e){
    //         echo $e;
    //     }
    // }

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
                        p.cedula as cedula_empleado, 
                        u.nombre as nombre, 
                        u.apellido1 as apellido, 
                        p.amount as adeudado, 
                        p.peticion_pago_id as planilla, 
                        p.date as fecha
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


    //devuelve los pagos pendientes de pagar
    public function getAllPagosPendientes(){
        $items = [];
        $estado = "pending";
        try{
          //regresa la union de donde la peticion_pago_id es igual al id de la tabla peticiones_pago con el usuario dado $userId
            $query = $this->prepare('SELECT p.id as id_pago, 
                             p.cedula as cedula_empleado, 
                             u.nombre as nombre, 
                             u.apellido1 as apellido, 
                             p.amount as adeudado, 
                             p.peticion_pago_id as planilla, 
                             p.date as fecha
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



    //devuelve los ultimos $n = 5 pagos hechos
    public function getPagosRecientes($n = 2){
        $items = [];
        $estado = "pagado";
        try{
          //regresa la union de donde la peticion_pago_id es igual al id de la tabla peticiones_pago con el usuario dado $userId
            $query = $this->prepare('SELECT p.id as id_pago, 
                             p.cedula as cedula_empleado, 
                             u.nombre as nombre, 
                             u.apellido1 as apellido, 
                             p.amount as adeudado, 
                             p.peticion_pago_id as planilla, 
                             p.date as fecha
                    FROM pagos AS p
                    INNER JOIN users AS u ON p.cedula = u.cedula
                    INNER JOIN peticiones_pago AS p2 ON p.peticion_pago_id  = p2.id
                    WHERE p.estado_pago = :estado      
                    ORDER BY p.date 
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
        $this->cedula = $array['cedula_empleado'];
        $this->nombre = $array['nombre'];
        $this->apellido = $array['apellido'];
        $this->amount = $array['adeudado'];
        $this->peticionPagoId = $array['planilla'];
        $this->date = $array['fecha'];
    }


    //
    public function toArray(){
        $array = [];
        $array['id_pago'] = $this->pagoId;
        $array['cedula_empleado'] = $this->cedula;
        $array['nombre'] = $this->nombre;
        $array['apellido'] = $this->apellido;
        $array['adeudado'] = $this->adeudado;
        $array['planilla'] = $this->peticionPagoId;
        $array['fecha'] = $this->date;

        return $array;
    }


    


    public function getPagoId(){return $this->pagoId;}
    public function getCedula(){return $this->cedula;}
    public function getNombre(){return $this->nombre;}
    public function getApellido(){return $this->apellido;}
    public function getAmount(){return $this->amount;}
    public function getPeticionPagoId(){return $this->peticionPagoId;}
    public function getDate(){return $this->date;}

    
}

?>