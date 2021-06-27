<?php

    class Controller{

        function __construct(){
            $this->view = new View();
        }
    
        function loadModel($model){
            $url = 'models/' . $model . 'model.php';

            if(file_exists($url)){
                require_once $url;

                // armando nombre del model camelCase
                $modelName = $model.'Model';
                $this->model = new $modelName();
            }
        }


    }

?>