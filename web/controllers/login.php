<?php
class Login extends Controller{

    function __construct(){
        parent::__construct();
        error_log('Login::construct -> inicio de Login');
        
    }

    function render(){
        error_log('Login::render -> Carga el index de login');
        $this->view->render('login/index');
    }
}

?>