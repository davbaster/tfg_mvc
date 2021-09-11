<?php

    // define('URL' , 'http://127.0.0.1:41062/www');//URL del servidor
    define('URL' , 'http://localhost:41062/www');//URL del servidor


    define('HOST', 'db');  //db es el nombre del servicio/servidor de bases de datos en docker-compose.yml
    //otros ejemplos de host
    // define('HOST', '127.0.0.1');
    //define('HOST', 'localhost');

    define('DB', 'db_user_system'); //db_user_system es el nombre de la base de datos

    define('USER', 'mariadb'); //mariadb es el usuario que maneja la base de datos

    define('PASSWORD', "pk515601"); //pk es la clave del usuario de la base de datos


    define('CHARSET', 'utf8mb4'); //La codificacion de caracteres a utilizar

?>