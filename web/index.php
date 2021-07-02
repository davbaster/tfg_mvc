<?php
        //Si usuario quiere devolverse, se redirigira a pagina principal 
        // session_start();
        // if(isset($_SESSION['user'])){
        //     header('location:principal.php');
        // }

        error_reporting(E_ALL);
        ini_set('ignore_repeated_errors', TRUE);
        ini_set('display_errors', FALSE);
        ini_set ('log_errors', TRUE); //guarde errores en un archivo.
        ini_set("error_log", "/www/php-error.log");
        error_log('Inicio de la aplicacion web');

        require_once 'libs/database.php';

        require_once 'classes/errormessages.php';
        require_once 'classes/successmessages.php';
        require_once 'libs/controller.php';
        require_once 'libs/model.php';
        require_once 'libs/view.php';

        require_once 'classes/sessioncontroller.php'; //se posiciona aca porque tiene que cargarse controller.php y view.php primero
        require_once 'libs/app.php';

        require_once 'config/config.php';

        $app = new App();
?>