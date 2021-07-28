<?php

class JoinPagosPeticionesModel extends Model {

    private $pagoId;
    private $title;
    private $amount;
    private $categoryId; //$peticionPagoId;
    private $peticionPagoId;
    private $date;
    private $userId;
    private $nameCategory;
    private $color;


    public function __construct()
    {
        parent::__construct();
    }


    //
    public function getAll($userId){
        $items = [];
        try{
          //regresa la union de donde la peticion_pago_id es igual al id de la tabla peticiones_pago con el usuario dado $userId
          //$query = $this->prepare('SELECT expenses.id as expense_id, title, category_id, amount, date, id_user, categories.id, name, color  FROM expenses INNER JOIN categories WHERE expenses.category_id = categories.id AND expenses.id_user = :userid ORDER BY date');
            $query = $this->prepare('SELECT pagos.id as pago_id, title, peticion_pago_id, amount, date, id_user, peticiones_pago.id, name, color  FROM pagos INNER JOIN peticiones_pago WHERE pagos.peticion_pago_id = peticiones_pago.id AND pagos.id_user = :userId ORDER BY date');
            $query->execute(["userId" => $userId]);

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
        $this->expenseId = $array['pago_id'];
        $this->title = $array['title'];
        $this->peticionPagoId = $array['peticion_pago_id'];
        $this->amount = $array['amount'];
        $this->date = $array['date'];
        $this->userId = $array['id_user'];
        $this->nameCategory = $array['name'];
        $this->color = $array['color'];
    }


    //
    public function toArray(){
        $array = [];
        $array['id'] = $this->pagoId;
        $array['title'] = $this->title;
        $array['peticion_pago_id'] = $this->peticionPagoId;
        $array['amount'] = $this->amount;
        $array['date'] = $this->date;
        $array['id_user'] = $this->userId;
        $array['name'] = $this->nameCategory;
        $array['color'] = $this->color;

        return $array;
    }


    


    public function getPagoId(){return $this->pagoId;}
    public function getTitle(){return $this->title;}
    public function getPeticionPagoId(){return $this->peticionPagoId;}
    public function getAmount(){return $this->amount;}
    public function getDate(){return $this->date;}
    public function getUserId(){return $this->userId;}
    public function getNameCategory(){return $this->nameCategory;}
    public function getColor(){return $this->color;}

    
}

?>