<?php

class Session{

    private $sessionName = 'user';

    public function __construct(){

        // entre si no hay session activa
        if(session_status() == PHP_SESSION_NONE){
            session_start(); //inicie session
        }
    }

    // asigna un usuario a la session
    public function setCurrentUser($user){
        $_SESSION[$this->sessionName] = $user;
    }

    // devuelve el usuario actual en session
    public function getCurrentUser(){
       return $_SESSION[$this->sessionName];
    }

    // destruye session
    public function  closeSession(){
        session_unset();
        session_destroy();
    }

    // regresa si existe o no la session
    public function exists(){
        return isset($_SESSION[$this->sessionName]);
    }
}

?>