<?php

require_once('model/functionsDB.php');
require_once('controller/procesarLogin.php');

function HTMLinicio($titulo)
{
    echo <<<HTML
    <!DOCTYPE html>
    <html>
    <head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>$titulo</title>
    </head>
    <body class="container-fluid" style="background-color: beige;">
    HTML;
}

function HTMLheader()
{
    echo <<<HTML
    <header class="container-fluid bg-light" style="background-color: beige;">
    <div class="row align-items-center justify-content-center text-center">
            <div class="col">
                <h1 class="display-4">Tu vecindario, ¡tu casa!</h1>
            </div>
            <div class="col">
                <img src="img/cenes.jpeg" id="img_cenes" class="img-fluid" alt="Imagen Cenes">
            </div>
        </div>

    </header>
    HTML;
}

function HTMLnav()
{
    echo <<<HTML
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
    HTML;
    if (!isset($_SESSION['rol'])) {
        $items = ["Incidencias"];
        $links = ["#"];
    } else {
        if ($_SESSION['rol'] == 'colaborador') {
            $items = ["Incidencias", "Mis incidencias"];
            $links = ["#", "#"];
        } else if ($_SESSION['rol'] == 'admin') {
            $items = ["Incidencias", "Mis incidencias", "Usuarios", "Logs", "BBDD"];
            $links = ["#", "#", "#", "#", "#"];
        }
    }
    foreach ($items as $index => $item) {
        $link = $links[$index];
        echo "<li class='nav-item'><a class='nav-link' href='$link'>$item</a></li>";
    }
    echo <<<HTML
            </ul>
        </div>
    </nav>
    HTML;
}

function HTMLmainContentStart()
{
    echo <<<HTML
    <div class="row">
    HTML;
}

function HTMLasideStart()
{
    echo <<<HTML
        <div class="col-lg-3">
            <aside style="background-color: beige;">
    HTML;
}

function HTMLaside()
{
    if (!isset($_SESSION['user'])) {
        echo <<<HTML
        <form method="POST" action="../controller/procesarLogin.php">
            <input type='text' placeholder='usuario' name='user' class="form-control" />
            <input type='password' name='contrasena' class="form-control" />
            <input type='submit' value='login' class='btn btn-primary btn-block' />
        </form>
        <form action = "../controller/register.php">
            <input type='submit' value='registrar' class='btnbtn-secondary btn-block' />
        </form>
    HTML;
    } else {
        echo <<<HTML
        <p>
            Bienvenido, {$_SESSION['user']}
        </p>
        <form action = "../controller/procesarLogout.php">
            <input type='submit' value='logout' class='btn btn-secondary btn-block' />
        </form>
    
    HTML;
    }
}

function HTMLasideEnd()
{
    echo <<<HTML
            </aside>
        </div>
    HTML;
}

function HTMLbodyStart()
{
    echo <<<HTML
        <div class="col-lg-9">
    HTML;
}

function HTMLbusqueda()
{
    echo <<<HTML
    <form method="POST" action="../controller/procesarBusqueda.php">
        <h2>Listado de incidencias</h2>
        <div class="container" style="background-color: beige;">
            <h3>Criterios de búsqueda</h3>
            <div class="row">
                <h4>Ordenar Por:</h4>
                <select class="form-select" name="ordenarPor">
                    <option value="sortAge">Antigüedad</option>
                    <option value="likes">Número de likes</option>
                    <option value="totalLikes">Likes netos</option>
                </select>
            </div>
            <div class="row">
                <h4>Incidencias que contengan:</h4>
                <input type="text" class="form-control" name="lugar" placeholder="Lugar">
                <input type="text" class="form-control" name="textoBusqueda" placeholder="Texto de búsqueda">
            </div>
            <div class="row">
                <div class="d-flex align-items-center">
                    <h4>Estado:</h4>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" value="opcion1" name="estado[]">
                        <label class="form-check-label" for="estado1">
                            Pendiente
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" value="opcion2" name="estado[]">
                        <label class="form-check-label" for="estado2">
                            Comprobada
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" value="opcion3" name="estado[]">
                        <label class="form-check-label" for="estado3">
                            Tramitada
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" value="opcion4" name="estado[]">
                        <label class="form-check-label" for="estado4">
                            Irresoluble
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" value="opcion5" name="estado[]">
                        <label class="form-check-label" for="estado5">
                            Resueltas
                        </label>
                    </div>
                </div>
            </div>
            <input type='submit' value='Buscar' class='btn btn-primary btn-block' />
    </form>
    HTML;
}


function HTMLbodyEnd()
{
    echo <<<HTML
        </div>
    </div>
    HTML;
}

function HTMLfooter()
{
    echo <<<HTML
    <footer class="bg-dark text-white text-center py-3 mt-auto">
        <div class="container">
            <p>Proyecto realizado por Jorge y Raúl</p>
        </div>
    </footer>
    HTML;
}

function HTMLfin()
{
    echo <<<HTML
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    </body>
    </html>
    HTML;
}

?>