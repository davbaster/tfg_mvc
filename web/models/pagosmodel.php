<?php

class PagosModel extends Model implements IModel {



    //************ */
    private $id;
    private $estadoPago; //antiguo tittle, ESTADOS: pagado, pendiente
    private $peticionPagoId;
    private $amount;
    private $date; //fecha pago
    private $userId; //id del usuario al que se le pago
   
    //TODO deberian haber adelantos?? o es mejor que haya un objeto adelanto que cuando se crea un pago busque adelantos y los liste
    //TODO deberian haber rebajos?? ose tiene que manejar en otra parte?
   

    //setters
    public function setId($id){ $this->id = $id; }
    public function setEstadoPago($estadoPago){ $this->estadoPago = $estadoPago; }
    public function setPeticionPagoId($peticionPagoId){ $this->peticionPagoId = $peticionPagoId; }//setPeticionPagoId
    public function setAmount($amount){ $this->amount = $amount; }
    public function setDate($date){ $this->date = $date; }
    public function setUserId($userid){ $this->userid = $userid; }

    //getters
    public function getId(){ return $this->id;}
    public function getEstadoPago(){ return $this->estadoPago; }
    public function getPeticionPagoId(){ return $this->peticionPagoId; } //setPeticionPagoId
    public function getAmount(){ return $this->amount; }
    public function getDate(){ return $this->date; }
    public function getUserId(){ return $this->userid; }


    public function __construct(){
        parent::__construct();

    }


    public function save(){
        try{
            $query = $this->prepare('INSERT INTO pagos (estado_pago, peticion_pago_id, amount,date, id_user) VALUES(:estadoPago, :peticionPagoId,:amount, :d, :user)');
            $query->execute([
                'estadoPago' => $this->estadoPago, 
                'peticionPagoId' => $this->peticionPagoId, 
                'amount' => $this->amount, 
                'd' => $this->date,
                'user' => $this->userId

            ]);

            //si devuelve resultado de una fila insertada
            if($query->rowCount()) return true;

            //sino
            return false;
        }catch(PDOException $e){
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
            return false;
        }
    }


    public function delete($id){
        try{
            $query = $this->prepare('DELETE FROM pagos WHERE id = :id');
            $query->execute([ 'id' => $id]);
            return true;
        }catch(PDOException $e){
            echo $e;
            return false;
        }
    }


    public function update(){
        try{
            $query = $this->prepare('UPDATE pagos SET 
                                    estado_pago = :estadoPago, 
                                    peticion_pago_id = :peticionPagoId, 
                                    amount = :amount,  
                                    date = :d, 
                                    id_user = :user 
                                    WHERE id = :id');

            $query->execute([
                'id' => $this->id,
                'estadoPago' => $this->estadoPago, 
                'peticionPagoId' => $this->peticionPagoId, 
                'amount' => $this->amount, 
                'd' => $this->date,
                'user' => $this->userid
            ]);
            return true;
        }catch(PDOException $e){
            echo $e;
            return false;
        }
    }


    public function from($array){
        $this->id = $array['id'];
        $this->estadoPago = $array['estadoPago'];
        $this->peticionPagoId = $array['peticion_pago_id'];
        $this->amount = $array['amount'];
        $this->date = $array['date'];
        $this->userid = $array['id_user'];
    }


    //Lista todos los pagos hechos a un usuario
    public function getAllByUserId($userid){
        $items = [];//listado de pagos

        try{
            $query = $this->prepare('SELECT * FROM pagos WHERE id_user = :userid');
            $query->execute([ "userid" => $userid]);

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
    public function getByUserIdAndLimit($userid, $n){
        $items = [];
        try{
            $query = $this->prepare('SELECT * FROM pagos WHERE id_user = :userid ORDER BY pagos.date DESC LIMIT 0, :n ');
            $query->execute([ 'n' => $n, 'userid' => $userid]);
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
    function getTotalAmountThisMonth($idUser){
        try{
            $year = date('Y');
            $month = date('m');
            //YEAR(date) (saca a;o actual) funcion de sql, MONTH(date) (saca mes actual) funcion sql
            $query = $this->db->connect()->prepare('SELECT SUM(amount) AS total FROM pagos WHERE YEAR(date) = :year AND MONTH(date) = :month AND id_user = :idUser');
            $query->execute(['year' => $year, 'month' => $month, 'idUser' => $idUser]);

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
            $query = $this->db->connect()->prepare('SELECT MAX(amount) AS total FROM pagos WHERE YEAR(date) = :year AND MONTH(date) = :month AND id_user = :idUser');
            $query->execute(['year' => $year, 'month' => $month, 'idUser' => $idUser]);

            $total = $query->fetch(PDO::FETCH_ASSOC)['total'];
            if($total == NULL) $total = 0;
            
            return $total;

        }catch(PDOException $e){
            return NULL;
        }
    }

    //total de pagos por planilla este mes
    //$categoryid podria ser el idContratista, para sacar la cantidad total de pagos que se hizo a un contratista en el mes
    //$categoryid podria ser el idPlanilla, para sacar el monto total de una planilla pagada 
    //getTotalByCategoryThisMonth
    // function getTotalByCategoryThisMonth($peticionPagoId, $userId){ 
    //     error_log("ExpensesModel::getTotalByCategoryThisMonth");
    //     try{
    //         $total = 0;
    //         $year = date('Y');
    //         $month = date('m');
    //         $query = $this->prepare('SELECT SUM(amount) AS total from pagos WHERE peticion_pago_id = :peticionPagoId AND id_user = :userId AND YEAR(date) = :year AND MONTH(date) = :month');
    //         $query->execute(['peticionPagoId' => $peticionPagoId, 'userId' => $userId, 'year' => $year, 'month' => $month]);
            
    //         $total = $query->fetch(PDO::FETCH_ASSOC)['total'];//trae la fila en la columna total
    //         if($total == NULL) return 0;
    //         return $total;

    //     }catch(PDOException $e){
    //         error_log("**ERROR: PagosModel::getTotalByCategoryThisMonth: error: " . $e);
    //         return NULL;
    //     }
    // }
    //lista la cantidad total pagada a un usuario en el mes actual.
    function getTotalByUserThisMonth($userId){ 
        error_log("ExpensesModel::getTotalByUserThisMonth");
        try{
            $total = 0;
            $year = date('Y');
            $month = date('m');
            $query = $this->prepare('SELECT SUM(amount) AS total from pagos WHERE id_user = :userId AND YEAR(date) = :year AND MONTH(date) = :month');
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
    function getTotalByMonthAndUserId($date,$userId){
        try{
            $total = 0;
            $year = substr($date, 0, 4);
            $month = substr($date, 5, 7);
            $query = $this->db->connect()->prepare('SELECT SUM(amount) AS total from pagos WHERE id_user = :user AND YEAR(date) = :year AND MONTH(date) = :month');
            $query->execute(['user' => $userId, 'year' => $year, 'month' => $month]);

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
    //getNumberOfPaymentsByCategoryThisMonth
    function getNumberOfPaymentsByCategoryThisMonth($peticionPagoId, $userId){
        try{
            $total = 0;
            $year = date('Y');
            $month = date('m');
            $query = $this->prepare('SELECT COUNT(id) AS total from pagos WHERE peticion_pago_id = :peticionPagoId AND id_user = :userId AND YEAR(date) = :year AND MONTH(date) = :month');
            $query->execute(['peticionPagoId' => $peticionPagoId, 'userId' => $userId, 'year' => $year, 'month' => $month]);

            $total = $query->fetch(PDO::FETCH_ASSOC)['total'];
            if($total == NULL) return 0;
            return $total;

        }catch(PDOException $e){
            return NULL;
        }
    }

}

?>