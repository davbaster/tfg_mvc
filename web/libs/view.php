<?php

    class View{

        function __construct(){

        }

        // $nombre = nombre de la vista
        // $data = parametros que se le van a pasar a la vista
        function render ($nombre, $data = []){
            $this->d = $data;

            // 
            $this->handleMessages();

            require 'views/' . $nombre . '.php';
        }

        private function handleMessages(){
            if(isset($_GET['success']) && isset($_GET['error'])  ){
                // error no puede existir success y error al mismo tiempo

            }else if(isset($_GET['success'])) {
                $this->handleSuccess();

            }else if(isset($_GET['error'])) {
                $this->handleError();
            }
        }


        // funcion que maneja los errores
        private function handleError(){
            $hash = $_GET['error'];
            $error = new ErrorMessages();

            // valida si existe la clave que viene en la url
            if($error->existKey($hash)){
                $this->d['error'] = $error->get($hash);

            }
        }


        // funcion que maneja los exitos
        private function handleSuccess(){
            $hash = $_GET['success'];
            $success = new SuccessMessages();

            // valida si existe la clave que viene en la url
            if($success->existKey($hash)){
                $this->d['success'] = $success->get($hash);

            }
        }

        // vistas
        public function showMessages(){
            $this->showErrors(); //removi corchetes
            $this->showSuccess();//removi corchetes
        }

        // imprime en html mensage de error
        public function showErrors(){
            if(array_key_exists('error', $this->d)){
                echo '<div class="error">' .$this->d['error'].'</div>';
            }
        }

        // imprime en html mensage de exito
        public function showSuccess(){
            if(array_key_exists('success', $this->d)){
                echo '<div class="success">' .$this->d['success'].'</div>';
            }
        }


    }

?>