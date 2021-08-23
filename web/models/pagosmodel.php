<?php



class PagosModel extends Model implements IModel {



    //************ */
    private $id;
    private $estadoPago; //open = acabado de crear, no se puede pagar hasta que pase a pendiente
                         //pendiente = ya se mando para autorizar, y esta autorizado. Esta esperando que se pague
                         //parcial??? se le hizo un adelanto //TODO verificar si es factible, o se crea la tabla adelantos                         
                         //pagado, 
    private $peticionPagoId;
    private $amount;
    private $fechaCreacion; 
    private $fechaPago; 
    private $cedula; //id del usuario al que se le pago
    private $detalles;
   
    //TODO deberian haber adelantos?? o es mejor que haya un objeto adelanto que cuando se crea un pago busque adelantos y los liste
    //TODO deberian haber rebajos?? ose tiene que manejar en otra parte?
   

    //setters
    public function setId($id){ $this->id = $id; }
    public function setEstadoPago($estadoPago){ $this->estadoPago = $estadoPago; }
    public function setPeticionPagoId($peticionPagoId){ $this->peticionPagoId = $peticionPagoId; }//setPeticionPagoId
    public function setAmount($amount){ $this->amount = $amount; }
    public function setFechaCreacion($fechaCreacion){ $this->fechaCreacion = $fechaCreacion; }
    public function setFechaPago($fechaPago){ $this->fechaPago = $fechaPago; }
    public function setCedula($cedula){ $this->cedula = $cedula; }
    public function setDetalles($detalles){ $this->detalles = $detalles; }

    //getters
    public function getId(){ return $this->id;}
    public function getEstadoPago(){ return $this->estadoPago; }
    public function getPeticionPagoId(){ return $this->peticionPagoId; } //setPeticionPagoId
    public function getAmount(){ return $this->amount; }
    public function getFechaCreacion(){ return $this->fechaCreacion; }
    public function getFechaPago(){ return $this->fechaPago; }
    public function getCedula(){ return $this->cedula; }
    public function getDetalles(){ return $this->detalles; }


    public function __construct(){
        parent::__construct();

        $this->id ='';
        $this->estadoPago ='';
        $this->peticionPagoId ='';
        $this->amount ='';
        $this->fechaCreacion ='';
        $this->fechaPago ='';
        $this->cedula ='';
        $this->detalles ='';


    }

    //recordar si las queries dan errores extranos de que el count de parametros es incorrecto, es posible que haya que inicializar 
    //las variables en el constructor
    public function save(){
        try{


            $query= $this->prepare("INSERT INTO pagos(estado_pago, peticion_pago_id, amount,fecha_pago, cedula, detalles) 
                                        VALUES (:estadoPago,:peticionPagoId,:amount,:fechaPago,:user,:detalles)");


            $query->execute([
                // 'id' => $this->id,
                'estadoPago' => $this->estadoPago, 
                'peticionPagoId' => $this->peticionPagoId, 
                'amount' => $this->amount, 
                // 'd' => $this->fechaCreacion,
                'fechaPago' => $this->fechaPago,
                'user' => $this->cedula,
                'detalles' => $this->detalles

            ]);

            //si devuelve resultado de una fila insertada
            if($query->rowCount()) return true;

            //sino
            return false;
        }catch(PDOException $e){
            error_log("PagosModel::save: " . $e);
            return false;
        }
    }

    //varias filas retornadas
    //crea varios objetos pago
    public function getAll(){
        $items = [];//arreglo de objetos de tipo pago

        try{
            $query = $this->query('SELECT * FROM pagos');

            //mientras que exista un objeto no null
            while($p = $query->fetch(PDO::FETCH_ASSOC)){
                $item = new PagosModel();
                //rellena objeto con informacion
                $item->from($p); 
                
                array_push($items, $item);
            }

            return $items;

        }catch(PDOException $e){
            error_log("PagosModel::getAll: " . $e);
            echo $e;
        }
    }

    
   
    public function get($id){
        try{
            $query = $this->prepare('SELECT * FROM pagos WHERE id = :id');
            $query->execute([ 'id' => $id]);
            $pago = $query->fetch(PDO::FETCH_ASSOC);

            //relleana objeto con la informacio mandadad en el objeto pago
            $this->from($pago);

            return $this;
        }catch(PDOException $e){
            error_log("PagosModel::get: " . $e);
            return false;
        }
    }

    

    public function delete($id){
        try{
            $query = $this->prepare('DELETE FROM pagos WHERE id = :id');
            $query->execute([ 'id' => $id]);
            return true;
        }catch(PDOException $e){
            //echo $e;
            error_log("PagosModel::delete: " . $e);
            return false;
        }
    }


    public function update(){
        try{
            $query = $this->prepare('UPDATE pagos SET 
                                    estado_pago = :estadoPago, 
                                    peticion_pago_id = :peticionPagoId, 
                                    amount = :amount,  
                                    fecha_creacion = :d, 
                                    fecha_pago = :p, 
                                    cedula = :user,
                                    detalles = :detalles
                                    WHERE id = :id');

            $query->execute([
                'id' => $this->id,
                'estadoPago' => $this->estadoPago, //FIXME estado pago esta saliendo null
                'peticionPagoId' => $this->peticionPagoId, 
                'amount' => $this->amount, 
                'd' => $this->fechaCreacion,
                'p' => $this->fechaCreacion,
                'user' => $this->cedula,
                'detalles' => $this->detalles
            ]);
            return true;
        }catch(PDOException $e){
            //echo $e;
            error_log("PagosModel::update: " . $e);
            return false;
        }
    }


    public function from($array){
        $this->id = $array['id'];
        $this->estadoPago = $array['estado_pago'];
        $this->peticionPagoId = $array['peticion_pago_id'];
        $this->amount = $array['amount'];
        $this->fechaCreacion = $array['fecha_creacion'];
        $this->fechaPago = $array['fecha_pago'];
        $this->cedula = $array['cedula'];
        $this->detalles = $array['detalles'];
    }



    //saca de la base de datos todos los pagos que esten pendientes de pago
    //retorna arreglo de pagos pendientes
    public function getPagosPendientes(){

        $items = [];//arreglo de objetos de tipo pago

        //$aprobado = 1;
        $estadoPago = "pendiente";

        try{
            
            $query = $this->prepare('SELECT * FROM pagos WHERE estado_pago = :estadopago');
            //$query = $this->prepare('SELECT * FROM pagos WHERE id = :id');
            $query->execute(['estadopago' => $estadoPago]);

            //mientras que exista un objeto no null
            while($p = $query->fetch(PDO::FETCH_ASSOC)){
                $item = new PagosModel();
                //rellena objeto con informacion
                $item->from($p); 
                
                array_push($items, $item);
            }

            return $items;

        }catch(PDOException $e){
            return false;
        }
    }


    //devuelve todos los pagos que tengan el id de la peticionPago dada.
    //todos los pagos que esten con el estado de open, osea esperando a que sean aprobados
    public function getAllByPeticionPagoId($idPeticionPago){
        $items = [];//listado de pagos
        $estado = "open";

        try{
            $query = $this->prepare('SELECT * FROM pagos 
                                    WHERE peticion_pago_id = :idPeticionPago
                                    AND estado_pago = :estado' );
            $query->execute([ "idPeticionPago" => $idPeticionPago,
                            "estado" => $estado]);

            while($p = $query->fetch(PDO::FETCH_ASSOC)){
                $item = new PagosModel();
                $item->from($p); 
                
                array_push($items, $item);
            }

            return $items;

        }catch(PDOException $e){
            return [];
            echo $e;
        }
    }




    // *********** funciones que se pueden mover a common *****************


    //Lista todos los pagos hechos a un usuario
    public function getAllByUserId($cedula){
        $items = [];//listado de pagos

        try{
            $query = $this->prepare('SELECT * FROM pagos WHERE cedula = :cedula');
            $query->execute([ "cedula" => $cedula]);

            while($p = $query->fetch(PDO::FETCH_ASSOC)){
                $item = new PagosModel();
                $item->from($p); 
                
                array_push($items, $item);
            }

            return $items;

        }catch(PDOException $e){
            return [];
            echo $e;
        }
    }



    //$n es el limite de elementos a listar
    public function getByUserIdAndLimit($cedula, $n){
        $items = [];
        try{
            $query = $this->prepare('SELECT * FROM pagos WHERE cedula = :cedula ORDER BY pagos.fechaCreacion DESC LIMIT 0, :n ');
            $query->execute([ 'n' => $n, 'cedula' => $cedula]);
            while($p = $query->fetch(PDO::FETCH_ASSOC)){
                $item = new PagosModel();
                $item->from($p); 
                
                array_push($items, $item);
            }
            error_log("PagosModel::getByUserIdAndLimit(): count: " . count($items));
            return $items;
        }catch(PDOException $e){
            return false;
        }
    }

    //regresa la suma total de pagos del mes
    function getTotalAmountThisMonth($cedula){
        try{
            $year = date('Y');
            $month = date('m');
            //YEAR(date) (saca a;o actual) funcion de sql, MONTH(date) (saca mes actual) funcion sql
            $query = $this->db->connect()->prepare('SELECT SUM(amount) AS total FROM pagos WHERE YEAR(date) = :year AND MONTH(date) = :month AND cedula = :cedula');
            $query->execute(['year' => $year, 'month' => $month, 'cedula' => $cedula]);

            $total = $query->fetch(PDO::FETCH_ASSOC)['total'];//total es la columna resultante de la suma en la DB
            if($total == NULL) $total = 0;
            
            return $total;

        }catch(PDOException $e){
            return NULL;
        }
    }

    //obtiene el pago mas grande del mes
    function getMaxPaymentThisMonth($idUser){
        try{
            $year = date('Y');
            $month = date('m');
            $query = $this->db->connect()->prepare('SELECT MAX(amount) AS total FROM pagos WHERE YEAR(date) = :year AND MONTH(date) = :month AND cedula = :cedula');
            $query->execute(['year' => $year, 'month' => $month, 'cedula' => $cedula]);

            $total = $query->fetch(PDO::FETCH_ASSOC)['total'];
            if($total == NULL) $total = 0;
            
            return $total;

        }catch(PDOException $e){
            return NULL;
        }
    }


    //lista la cantidad total pagada a un usuario en el mes actual.
    function getTotalByUserThisMonth($cedula){ 
        error_log("ExpensesModel::getTotalByUserThisMonth");
        try{
            $total = 0;
            $year = date('Y');
            $month = date('m');
            $query = $this->prepare('SELECT SUM(amount) AS total from pagos WHERE cedula = :cedula AND YEAR(date) = :year AND MONTH(date) = :month');
            $query->execute(['userId' => $userId, 'year' => $year, 'month' => $month]);
            
            $total = $query->fetch(PDO::FETCH_ASSOC)['total'];//trae la fila en la columna total
            if($total == NULL) return 0;
            return $total;

        }catch(PDOException $e){
            error_log("**ERROR: PagosModel::getTotalByUserThisMonth: error: " . $e);
            return NULL;
        }
    }

    //TODO hacer metodo que liste todos los pagos dado un userID  MES / CANTIDAD

    //lista la cantidad total pagada a un usuario dado el mes actual.
    //getTotalByMonthAndCategory 
    function getTotalByMonthAndUserId($date,$cedula){
        try{
            $total = 0;
            $year = substr($date, 0, 4);
            $month = substr($date, 5, 7);
            $query = $this->db->connect()->prepare('SELECT SUM(amount) AS total from pagos WHERE cedula = :cedula AND YEAR(date) = :year AND MONTH(date) = :month');
            $query->execute(['cedula' => $cedula, 'year' => $year, 'month' => $month]);

            if($query->rowCount() > 0){
                $total = $query->fetch(PDO::FETCH_ASSOC)['total'];
            }else{
                return 0;
            }
            
            return $total;

        }catch(PDOException $e){
            return NULL;
        }
    }

    //TODO BORRAR si no se ocupa
    //Puede contar los pagos por planilla este mes
    //$categoryid podria ser el idContratista, para poder contar los pagos que se hizo por contratista en el mes
    //$categoryid podria ser el idPlanilla, para poder contar los pagos que se hicieron a una planilla pagada este mes
    //userID tambien se podria usar para ver los pagos hechos a un usuario en el mes
    //getNumberOfPaymentsByCategoryThisMonth
    function getNumberOfPaymentsByUserThisMonth($peticionPagoId, $cedula){
        try{
            $total = 0;
            $year = date('Y');
            $month = date('m');
            $query = $this->prepare('SELECT COUNT(id) AS total from pagos WHERE peticion_pago_id = :peticionPagoId AND cedula = :cedula AND YEAR(date) = :year AND MONTH(date) = :month');
            $query->execute(['peticionPagoId' => $peticionPagoId, 'cedula' => $cedula, 'year' => $year, 'month' => $month]);

            $total = $query->fetch(PDO::FETCH_ASSOC)['total'];
            if($total == NULL) return 0;
            return $total;

        }catch(PDOException $e){
            return NULL;
        }
    }

}

?>