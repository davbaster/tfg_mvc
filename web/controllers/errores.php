<?php

    class Errores extends Controller {

        function __construct(){
            parent::__construct();
            error_log('Errores::construct -> inicio de Errores');
        }

        function render(){
            //error_log('Errores::render -> Carga el index de Errores');
            $this->view->render('errores/index');
        }


    }

?>