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
            </ul>
        </div>
    </nav>
    HTML;
}



function HTMLaside() {
    if($_SESSION['user'] == ""){
    echo <<<HTML
        <form method="POST" action="../controller/procesarLogin.php">
            <input type='text' placeholder='usuario' name='user' class="form-control" />
            <input type='password' name='contrasena' class="form-control" />
            <input type='submit' value='login' class='btn btn-primary btn-block' />
        </form>
        <form action = "../controller/register.php">
            <input type='submit' value='registrar' class='btn btn-secondary btn-block' />
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

// function HTMLaside() {
//     echo <<<HTML
//     <aside class="col-3 bg-beige d-flex flex-column p-3">
//         <div class="card">
//             <div class="card-body">
//                 <h5 class="card-title text-center">Iniciar sesión</h5>
//                 <form action="procesarLogin.php" method="POST">
//                     <div class="form-group">
//                         <label for="email">Correo electrónico</label>
//                         <input type="email" class="form-control" id="email" name="user" required>
//                     </div>
//                     <div class="form-group mt-3">
//                         <label for="password">Contraseña</label>
//                         <input type="password" class="form-control" id="password" name="contrasena" required>
//                     </div>
//                     <div class="d-grid gap-2 mt-4">
//                         <button type="submit" class="btn btn-primary btn-block">Iniciar sesión</button>
//                     </div>
//                 </form>
//             </div>
//         </div>
//         <div class="d-grid gap-2 mt-4">
//             <form action="../controller/register.php" method="POST">
//                 <button type="submit" class="btn btn-secondary btn-block">Registrar</button>
//             </form>
//         </div>
//     </aside>
//     HTML;
// }



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
        <div class="col-lg-2">
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