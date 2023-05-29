<?php

require_once('model/functionsDB.php');

function HTMLinicio($titulo){
    echo <<< HTML
    <!DOCTYPE html>
    <html>
    <head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link  href="vista.css" rel="stylesheet" type="text/css">
    <title>$titulo</title>
    </head>
    <body class="container-fluid" style="background-color: beige;">
    HTML;
}

function HTMLheader() {
    echo <<<HTML
    <header class="container-fluid" style="background-color: beige;">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="display-4">Tu vecindario, tu casa</h1>
            </div>
            <div class="col">
                <img src="img/cenes.jpeg" id="img_cenes" class="img-fluid" alt="Imagen Cenes">
            </div>
        </div>
    </header>
    HTML;
}

function HTMLfin(){
    echo <<< HTML
    </body>
    HTML;
}

function HTMLnav() {
    echo <<< HTML
    <nav class="navbar navbar-expand-lg navbar-light bg-light" style="background-color: beige;">
        <div class="container">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="#">Incidencias</a>
                </li>
    HTML;
    if(loginDB('Juan','Pérez','proyectoTW')) echo '<li class="nav-item"> <a class="nav-link" href="#">Usuarios</a> </li>';
    echo <<< HTML
            </ul>
        </div>
    </nav>
    HTML;
}

function HTMLaside() {
    echo <<<HTML
    <aside class="col-3" style="background-color: beige; display:flex;">
        <form action="">
            <input type='text' placeholder='usuario' name='user' class="form-control" />
            <input type='password' name='contrasena' class="form-control" />
            <input type='submit' value='login' class='btn btn-primary btn-block' />
            <input type='submit' value='registrar' class='btn btn-secondary btn-block' />
        </form>
    </aside>
    HTML;
}



function HTMLfooter() {
    echo <<<HTML
    <footer class="bg-dark text-white text-center py-3 mt-auto">
        <div class="container">
            <p>Proyecto realizado por Jorge y Raúl</p>
        </div>
    </footer>
    </html>
    HTML;
}

?>