<?php

    session_start();
    require_once 'auth.php';
    $cuser = new Auth();

    // sino se ha creado una session para el usuario, y este trata de entrar, enviarlo a login
    if(!isset($_SESSION['user'])){
        //
        header('location:index.php');
        die;
    }

    // current cedula (determina al usuario con cedula)
    $cCedula = $_SESSION['user'];

    // data variable contiene los datos del usuario actual
    $data = $cuser->currentUser($cCedula);

?>