<link rel="stylesheet" href="public/css/default.css">
<link rel="stylesheet" href="public/css/dashboard.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">


<div id="header">
    <ul>
        <li><a href="dashboard">Home</a></li>
        <li><a href="peticionespago">Planillas</a></li>
        <li><a href="pagos">Pagos</a></li>
        <li><a href="signup">Usuarios</a></li>
        <!-- <li><a href="<?php //echo constant('URL'); ?>/user">Configuracion</a></li> -->
        <li><a href="user">Configuracion</a></li>
        <li><a href="logout">Salir</a></li>
    </ul>
    <div id="profile-container">
        <a href="user">
            <div class="name"></div>
            <div class="photo">
            </div>
        </a>
        <div id="submenu">
            <ul>
                <li><a href="user">Ver perfil</a></li>
                <li class='divisor'></li>
                <li><a href="logout">Cerrar sesión</a></li>
            </ul>
        </div>
    </div>
</div>