<?php

require_once('model/functionsDB.php');
require_once('controller/procesarLogin.php');

function HTMLinicio($titulo){
    echo <<< HTML
    <!DOCTYPE html>
    <html>
    <head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>$titulo</title>
    </head>
    <body class="container-fluid" style="background-color: beige;">
    HTML;
}

function HTMLheader() {
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
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container">
                <a class="navbar-brand" href="#"></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="#">Incidencias</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
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
            <ul class="navbar-nav justify-content-center">
                <li class="nav-item mx-auto"> <!-- Agregado mx-auto -->
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
        <form action="procesarLogin.php">
            <input type='text' placeholder='usuario' name='user' class="form-control" />
            <input type='password' name='contrasena' class="form-control" />
            <input type='submit' value='login' class='btn btn-primary btn-block' />
        </form>
        <form action = "../controller/register.php">
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

function HTMLmainContentStart(){
    echo <<<HTML
    <div class="row">
        <div class="col-lg-9">
    HTML;
}


function HTMLasideStart(){
    echo <<<HTML
        </div>
        <div class="col-lg-1">
            <aside style="background-color: beige;">
    HTML;
}

function HTMLasideEnd(){
    echo <<<HTML
            </aside>
        </div>
    </div>
    HTML;
}


?>