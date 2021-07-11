<?php

class Dashboard extends SessionController{

    function __construct(){
        parent::__construct();
        error_log('Dashboard::construct -> inicio de Dashboard');
        
    }

    function render(){
        error_log('Dashboard::render -> Carga el index del Dashboard');
        $this->view->render('dashboard/index');
    }


}


?>