<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Aplicacion Planillas</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/Login-Center.css">
    <link rel="stylesheet" href="assets/css/offcanvas.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body class="bg-light"><div>
    <?php
        //Si usuario quiere devolverse, se redirigira a pagina principal 
        require_once './php/session.php';
    ?>
</div><nav class="navbar navbar-expand-lg fixed-top navbar-dark bg-dark" aria-label="Main navigation">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">JyC</a>
      <button class="navbar-toggler p-0 border-0" type="button" id="navbarSideCollapse" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
  
      <div class="navbar-collapse offcanvas-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="#">Inicio</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Rebajos</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Pagos</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Planillas</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Reportes</a>
          </li>
            
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-bs-toggle="dropdown" aria-expanded="false">Usuarios</a>
            <ul class="dropdown-menu" aria-labelledby="dropdown01">
              <li><a class="dropdown-item" href="usuarios.html">Admin</a></li>
              <li><a class="dropdown-item" href="#">Buscar</a></li>
              <li><a class="dropdown-item" href="#">Listar</a></li>
            </ul>
          </li>
        </ul>
          <div class="d-flex"><a href="./php/logout.php">Salir</a></div>
<!--         <form class="d-flex">
          <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
          <button class="btn btn-outline-success" type="submit">Search</button>
        </form> -->
      </div>
    </div>
</nav>
    <div class="nav-scroller bg-body shadow-sm">
        <div id="nav-secundario" class="nav nav-underline"><a class="nav-link active" href="#">Inicio</a><a class="nav-link" href="#">Link</a><a class="nav-link" href="#">Link</a></div>
    </div>
    <main class="container">
        <div id="bloque-1-main" class="d-flex align-items-center p-3 my-3 text-white bg-purple rounded shadow-sm"><img src="https://getbootstrap.com/docs/5.0/assets/brand/bootstrap-logo-white.svg" width="48" height="38">
            <div class="lh-1">
                <h1 class="h6 mb-0 text-white lh-1">Sistema de Planillas</h1><small>Jimenez y Cordoba</small>
            </div>
        </div>
        <div id="bloque-2-main" class="my-3 p-3 bg-body rounded shadow-sm">
            <h6 class="border-bottom pb-2 mb-0">Tareas</h6>
            <div class="d-flex text-muted pt-3"><svg class="bd-placeholder-img flex-shrink-0 me-2 rounded" width="32" height="32" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: 32x32" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#007bff"/><text x="50%" y="50%" fill="#007bff" dy=".3em">32x32</text></svg><div>
<?php
    require_once './php/session.php';
    echo '<pre>';
    print_r($data);
?>
</div></div>
            <div class="d-flex text-muted pt-3"><svg class="bd-placeholder-img flex-shrink-0 me-2 rounded" width="32" height="32" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: 32x32" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#007bff"/><text x="50%" y="50%" fill="#007bff" dy=".3em">32x32</text></svg>
                <p class="pb-3 mb-0 small lh-sm border-bottom"><strong class="d-block text-gray-dark">@username</strong>Some representative placeholder content, with some information about this user. Imagine this being some sort of status update, perhaps?</p>
            </div>
            <div class="d-flex text-muted pt-3"><svg class="bd-placeholder-img flex-shrink-0 me-2 rounded" width="32" height="32" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: 32x32" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#007bff"/><text x="50%" y="50%" fill="#007bff" dy=".3em">32x32</text></svg>
                <p class="pb-3 mb-0 small lh-sm border-bottom"><strong class="d-block text-gray-dark">@username</strong>Some representative placeholder content, with some information about this user. Imagine this being some sort of status update, perhaps?</p>
            </div><small class="d-block text-end mt-3"><a href="#">All updates</a></small>
        </div>
        <div id="bloque-3-main" class="my-3 p-3 bg-body rounded shadow-sm">
            <h6 class="border-bottom pb-2 mb-0">Sugerencias</h6>
            <div class="d-flex text-muted pt-3"><svg class="bd-placeholder-img flex-shrink-0 me-2 rounded" width="32" height="32" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: 32x32" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#007bff"/><text x="50%" y="50%" fill="#007bff" dy=".3em">32x32</text></svg>
                <p class="pb-3 mb-0 small lh-sm border-bottom"><strong class="d-block text-gray-dark">@username</strong>Some representative placeholder content, with some information about this user. Imagine this being some sort of status update, perhaps?</p>
            </div>
            <div class="d-flex text-muted pt-3"><svg class="bd-placeholder-img flex-shrink-0 me-2 rounded" width="32" height="32" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: 32x32" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#007bff"/><text x="50%" y="50%" fill="#007bff" dy=".3em">32x32</text></svg>
                <p class="pb-3 mb-0 small lh-sm border-bottom"><strong class="d-block text-gray-dark">@username</strong>Some representative placeholder content, with some information about this user. Imagine this being some sort of status update, perhaps?</p>
            </div>
            <div class="d-flex text-muted pt-3"><svg class="bd-placeholder-img flex-shrink-0 me-2 rounded" width="32" height="32" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: 32x32" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#007bff"/><text x="50%" y="50%" fill="#007bff" dy=".3em">32x32</text></svg>
                <p class="pb-3 mb-0 small lh-sm border-bottom"><strong class="d-block text-gray-dark">@username</strong>Some representative placeholder content, with some information about this user. Imagine this being some sort of status update, perhaps?</p>
            </div><small class="d-block text-end mt-3"><a href="#">All updates</a></small>
        </div>
    </main>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/action.js"></script>
    <script src="assets/js/offcanvas.js"></script>
    <script src="assets/js/table.js"></script>
</body>

</html>