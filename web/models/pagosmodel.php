<?php

class PagosModel extends Model implements IModel {



    //************ */
    private $id;
    private $amount;
    private $title;
    private $categoryId; //idOrdenPago
    private $date; //fecha pago
    private $userId; //id del usuario al que se le pago

    private $idContrato;
    private $estadoPago;//bool


    public function setId($id){ $this->id = $id; }
    public function setTitle($title){ $this->title = $title; }
    public function setAmount($amount){ $this->amount = $amount; }
    public function setCategoryId($categoryid){ $this->categoryid = $categoryid; }
    public function setDate($date){ $this->date = $date; }
    public function setUserId($userid){ $this->userid = $userid; }

    public function getId(){ return $this->id;}
    public function getTitle(){ return $this->title; }
    public function getAmount(){ return $this->amount; }
    public function getCategoryId(){ return $this->categoryid; }
    public function getDate(){ return $this->date; }
    public function getUserId(){ return $this->userid; }


    public function __construct(){
        parent::__construct();

    }


    public function save(){
        try{
            $query = $this->prepare('INSERT INTO pagos (title, amount, category_id, date, id_user) VALUES(:title, :amount, :category, :d, :user)');
            $query->execute([
                'title' => $this->title, 
                'amount' => $this->amount, 
                'category' => $this->categoryId, 
                'user' => $this->userId, 
                'd' => $this->date
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
            $query = $this->prepare('UPDATE pagos SET title = :title, amount = :amount, category_id = :category, date = :d, id_user = :user WHERE id = :id');
            $query->execute([
                'title' => $this->title, 
                'amount' => $this->amount, 
                'category' => $this->categoryid, 
                'user' => $this->userid, 
                'd' => $this->date
            ]);
            return true;
        }catch(PDOException $e){
            echo $e;
            return false;
        }
    }


    public function from($array){
        $this->id = $array['id'];
        $this->title = $array['title'];
        $this->amount = $array['amount'];
        $this->categoryid = $array['category_id'];
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
    function getTotalByCategoryThisMonth($categoryid, $userId){
        error_log("ExpensesModel::getTotalByCategoryThisMonth");
        try{
            $total = 0;
            $year = date('Y');
            $month = date('m');
            $query = $this->prepare('SELECT SUM(amount) AS total from pagos WHERE category_id = :categoryid AND id_user = :userId AND YEAR(date) = :year AND MONTH(date) = :month');
            $query->execute(['categoryid' => $categoryid, 'userId' => $userId, 'year' => $year, 'month' => $month]);
            
            $total = $query->fetch(PDO::FETCH_ASSOC)['total'];//trae la fila en la columna total
            if($total == NULL) return 0;
            return $total;

        }catch(PDOException $e){
            error_log("**ERROR: ExpensesModel::getTotalByCategoryThisMonth: error: " . $e);
            return NULL;
        }
    }


    //Puede contar los pagos por planilla este mes
    //$categoryid podria ser el idContratista, para poder contar los pagos que se hizo por contratista en el mes
    //$categoryid podria ser el idPlanilla, para poder contar los pagos que se hicieron a una planilla pagada este mes
    function getNumberOfPaymentsByCategoryThisMonth($categoryid, $userId){
        try{
            $total = 0;
            $year = date('Y');
            $month = date('m');
            $query = $this->prepare('SELECT COUNT(id) AS total from pagos WHERE category_id = :categoryid AND id_user = :userId AND YEAR(date) = :year AND MONTH(date) = :month');
            $query->execute(['categoryid' => $categoryid, 'userId' => $userId, 'year' => $year, 'month' => $month]);

            $total = $query->fetch(PDO::FETCH_ASSOC)['total'];
            if($total == NULL) return 0;
            return $total;

        }catch(PDOException $e){
            return NULL;
        }
    }

}

?>