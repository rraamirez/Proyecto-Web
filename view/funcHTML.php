<?php

require_once(__DIR__ . '/../controller/procesarLogin.php');
require_once(__DIR__ . '/../model/bd.php');


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

function HTMLheader($index)
{
    if($index == 1){
        $ruta_f = "img/cenes.jpeg";
    }
    else{
        $ruta_f = "../img/cenes.jpeg";
    }
    echo <<<HTML
    <header class="container-fluid bg-light" style="background-color: beige;">
    <div class="row align-items-center justify-content-center text-center">
            <div class="col">
                <h1 class="display-4">Tu vecindario, ¡tu casa!</h1>
            </div>
            <div class="col">
                <img src=$ruta_f id="img_cenes" class="img-fluid" alt="Imagen Cenes">
            </div>
        </div>

    </header>
    HTML;
}

function HTMLnav($index)
{
    echo <<<HTML
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
    HTML;
    if (!isset($_SESSION['rol'])) {
        $items = ["Incidencias"];
        if($index == 1)
            $links = ["controller/verIncidencias.php"];
        else
            $links = ["verIncidencias.php"];
    } else {
        if ($_SESSION['rol'] == 'colaborador') {
            $items = ["Incidencias", "Mis incidencias", "Nueva Incidencia"];
            $links = ["verIncidencias.php", "#", "/view/nuevaIncidencia.php"];
        } else if ($_SESSION['rol'] == 'admin') {
            $items = ["Incidencias","Nueva Incidencia", "Mis incidencias", "Usuarios", "Logs", "BBDD"];
            $links = ["verIncidencias.php", "/view/nuevaIncidencia.php", "#", "#", "#", "#"];
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
    <div class="row align-items-center justify-content-center">
    HTML;
}

function HTMLbienvenidaStart()
{
    echo <<<HTML
        <div class="col-lg-9 p-3 border border-primary border-3 rounded shadow-lg mt-3 mb-3" >
    HTML;
}

function HTMLbienvenido(){
    echo <<<HTML
    <h2 class="text-center">Bienvenido a tu vecindario</h2>
    <p class="text-center">Aquí podrás ver las incidencias de tu comunidad, así como crear nuevas incidencias y ver el estado de las mismas.</p>
    HTML;
}

function HTMLbienvenidaEnd()
{
    echo <<<HTML
        </div>
    HTML;
}
function HTMLasideStart()
{
    echo <<<HTML
        <div class="col-lg-2 ml-auto">
            <aside class="p-3 border border-primary border-3 rounded shadow-lg mt-3 mb-3">
    HTML;
}



function HTMLaside()
{
    if (!isset($_SESSION['user'])) {
        echo <<<HTML
        <form method="POST" action="../controller/procesarLogin.php" class="form-signin">
            <label for="inputUser" class="sr-only">Usuario</label>
            <input type="text" id="inputUser" name='user' class="form-control" placeholder="Usuario" required autofocus>
            <label for="inputPassword" class="sr-only">Contraseña</label>
            <input type="password" id="inputPassword" name='contrasena' class="form-control" placeholder="Contraseña" required>
            <div class="d-flex justify-content-center mt-3">
                <button class="btn btn-lg btn-primary" type="submit">Iniciar sesión</button>
            </div>
        </form>
        <form action="../controller/register.php" class="form-signin">
            <div class="d-flex justify-content-center mt-3">
                <button class="btn btn-lg btn-secondary" type="submit">Registrar</button>
            </div>
        </form>
    HTML;
    } else {
        // Convertir la foto a base64 para poder mostrarla en un elemento de imagen
        $db = new Conexion();
        $db->conectar();
        $foto = $db->getImage($_SESSION['user']);
        $db->desconectar();

        echo <<<HTML

        <p class="text-center">
            Bienvenido, {$_SESSION['user']}
        </p>
        <div class="text-center">
            <img src="data:image/jpg;base64,$foto" class="img-fluid rounded-circle" style="width: 100px; height: auto; object-fit: cover;" alt="Foto de perfil">
        </div>

        <div class="d-flex justify-content-center mt-3">
            <form action="../controller/editarUsuario.php" class="form-signin" style="margin-left: 10px; margin-top: 10px;">
                <button class="btn btn-lg">
                    <img src="../img/edit_icon.png" alt="Editar" style="width: 50px; height: 50px;"/>
                </button>
            </form>
            <form action="../controller/procesarLogout.php" class="form-signin">
                <button class="btn btn-lg btn-secondary" type="submit">Cerrar sesión</button>
            </form>
            
        </div>
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
        <div class="col-lg-9 p-3 border border-primary border-3 rounded shadow-lg mt-3 mb-3" >
    HTML;
}

function HTMLbusqueda()
{
    echo <<<HTML
    <form method="POST" action="../controller/procesarBusqueda.php">
        <h2 class="border">Listado de incidencias:</h2>
        <div class="container border" style="background-color: beige;">
            <h3>Criterios de búsqueda</h3>
            <div class="row border">
                <h4>Ordenar Por:</h4>
                <select class="form-select" name="ordenarPor">
                    <option value="sortAge">Antigüedad</option>
                    <option value="likes">Número de likes</option>
                    <option value="totalLikes">Likes netos</option>
                </select>
            </div>
            <div class="row border">
                <h4>Incidencias que contengan:</h4>
                <input type="text" class="form-control" name="lugar" placeholder="Lugar">
                <input type="text" class="form-control" name="textoBusqueda" placeholder="Texto de búsqueda">
            </div>
            <div class="row border">
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

function HTMLbodyIncidenciasStart()
{
    echo <<<HTML
        <div class="col-lg-9 p-3 border border-primary border-3 rounded shadow-lg mt-3 mb-3" >
    HTML;
}

function HTMLbodyIncidencias()
{
    echo 'aquí tienen que salir las incidencias filtradas';
}

function HTMLbodyIncidenciasEnd()
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