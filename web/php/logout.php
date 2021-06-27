<?php 

    session_start();
    unset($_SESSION['user']);
    // redirect user to login page
    header('location:../index.php');


?>