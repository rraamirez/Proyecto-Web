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
            if($index == 1)
                $links = ["controller/verIncidencias.php", "controller/misIncidencias.php", "controller/nuevaIncidencia.php"];
            else
                $links = ["verIncidencias.php", "misIncidencias.php", "nuevaIncidencia.php"];
        } else if ($_SESSION['rol'] == 'admin') {
            $items = ["Incidencias","Mis incidencias","Nueva Incidencia", "Usuarios", "Logs", "BBDD"];
            if($index == 1)
                $links = ["/controller/verIncidencias.php", "/controller/misIncidencias.php", "/controller/nuevaIncidencia.php", "/controller/verUsuarios.php", "/controller/logs.php", "/controller/verBaseDatos.php"];
            else
            $links = ["verIncidencias.php", "misIncidencias.php", "nuevaIncidencia.php", "verUsuarios.php", "logs.php", "verBaseDatos.php"];    
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

function HTMLbbdd() {
    echo <<<HTML
    <h2 class="text-center">Base de datos</h2>
    <p class="text-center">Aquí podrás exportar, importar y borrar tu base de datos.</p>
    <div class="d-flex justify-content-between">
        <form action="../model/exportar.php" method="post">
            <button type="submit" class="btn btn-outline-primary">Exportar</button>
        </form>
        <form action="../model/importar.php" method="post" enctype="multipart/form-data">
            <input type="file" name="backupFile" required>
            <button type="submit" class="btn btn-outline-success">Importar</button>
        </form>
        <form action="../model/borrarBBDD.php" method="post" onsubmit="return confirm('¿Estás seguro de que quieres borrar la base de datos? Esta acción no se puede deshacer. Continúe sólo si sabe lo que está haciendo.');">
            <button type="submit" class="btn btn-outline-danger">Borrar Base de Datos</button>
        </form>
    </div>
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
    <form method="POST" action="procesarBusqueda.php">
        <h2 class="border">Listado de incidencias:</h2>
        <div class="container border" style="background-color: beige;">
            <h3>Criterios de búsqueda</h3>
            <div class="row border">
                <h4>Ordenar Por:</h4>
                <select class="form-select" name="ordenarPor">
                    <option value=0>Antigüedad</option>
                    <option value=1>Número de likes</option>
                    <option value=2>Likes netos</option>
                </select>
            </div>
            <div class="row border">
                <h4>Incidencias que contengan:</h4>
                <input type="text" class="form-control" name="lugar" placeholder="Lugar">
                <input type="text" class="form-control" name="textoBusqueda" placeholder="Palabras clave">
            </div>
            <div class="row border">
                <h4>Número de incidencias por página:</h4>
                <select class="form-select" name="incidenciasPorPagina">
                    <option value=3>3</option>
                    <option value=20>20</option>
                    <option value='todas'>Todas</option>
                </select>
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

function HTMLContentStart()
{
    echo <<<HTML
        <div class="col-lg-9 p-3 border border-primary border-3 rounded shadow-lg mt-3 mb-3">
    HTML;
}

function HTMLIncidencias()
{
    //FALTA BUSQUEDA POR ESTADO
    $db = new Conexion();
    $db->conectar();

    $ordenarPor = isset($_SESSION['ordenarPor']) && !empty($_SESSION['ordenarPor']) 
                  ? $_SESSION['ordenarPor'] : 0;

    $textoBusqueda = isset($_SESSION['textoBusqueda']) && !empty($_SESSION['textoBusqueda']) 
                     ? $_SESSION['textoBusqueda'] : null;

    $lugar = isset($_SESSION['lugar']) && !empty($_SESSION['lugar']) 
             ? $_SESSION['lugar'] : null;

    $incidenciasPorPagina = isset($_SESSION['incidenciasPorPagina']) && !empty($_SESSION['incidenciasPorPagina']) 
                            ? $_SESSION['incidenciasPorPagina'] : 3;

    $estado = isset($_SESSION['estado']) && !empty($_SESSION['estado']) 
              ? $_SESSION['estado'] : "Pendiente";

    // Llama a la función de búsqueda de incidencias.
    $incidencias = $db->searchIncidencias($incidenciasPorPagina, $ordenarPor, $lugar, $textoBusqueda, $estado);

    // Comprueba si se encontraron incidencias.
    if (empty($incidencias)) {
        echo 'No se encontraron incidencias.';
    } else {
        // Itera a través de cada incidencia.
        foreach ($incidencias as $incidencia) {
            echo <<<HTML
            <link href="../view/vista.css" rel="stylesheet">
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title">{$incidencia['titulo']}</h5>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <span class="keyword">Lugar:</span> <span class="value">{$incidencia['ubicacion']}</span>
                        <span class="keyword">Fecha:</span> <span class="value">{$incidencia['fecha']}</span>
                        <span class="keyword">Usuario:</span> <span class="value">{$db->getUsuario($incidencia['id_usuario'])}</span>
                    </li>
                    <li class="list-group-item">
                        <span class="keyword">Palabras clave:</span> <span class="value">{$incidencia['palabras_clave']}</span>
                        <span class="keyword">Estado:</span> <span class="value">{$incidencia['estado']}</span>
                        <span class="keyword">Valoraciones:</span> <span class="value">+{$incidencia['val_pos']} -{$incidencia['val_neg']}</span>
                    </li>
                </ul>
                <div class="card-body">
                    <p class="card-text">{$incidencia['descripcion']}</p>
                </div>
            HTML;

            $fotos = $db->searchFotos($incidencia['id_incidencia']);

            echo '<div class="foto-container">';
            foreach ($fotos as $foto) {
                echo <<<HTML
                    <img class="foto" src="data:image/jpg;base64,$foto" alt="Foto de la incidencia">
                HTML;
            }
            echo '</div>';  

            $comentarios = $db->searchComentarios($incidencia['id_incidencia']);
        
            foreach ($comentarios as $comentario) {
                if($comentario['id_usuario'] != null)
                    $nombre = $db->getUsuario($comentario['id_usuario']);
                else
                    $nombre = "Anónimo";
                echo <<<HTML
                    <div class="row comentario">
                        <div class="col-sm-6">
                            <div class="d-flex align-items-start">
                                <div class="comment-username">
                                    <strong>{$nombre}</strong>
                                </div>
                                <div class="comment-date">
                                    {$comentario['fecha']}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <p>{$comentario['mensaje']}</p>
                        </div>
                    </div>
                HTML;
            }            
        
            echo <<<HTML
                <div class="card-footer d-flex justify-content-end">
                    <form action="procesarValoracion.php" method="POST" class="mr-1">
                        <input type="hidden" name="idIncidencia" value="{$incidencia['id_incidencia']}">
                        <input type="hidden" name="valoracion" value="1">
                        <button type="submit" class="btn btn-success btn-circle btn-sm">
                            <img src="../img/plus.png" alt="Positive" style="width: 15px; height: 15px;">
                        </button>
                    </form>
                    <form action="procesarValoracion.php" method="POST" class="mr-1">
                        <input type="hidden" name="idIncidencia" value="{$incidencia['id_incidencia']}">
                        <input type="hidden" name="valoracion" value="-1">
                        <button type="submit" class="btn btn-danger btn-circle btn-sm">
                            <img src="../img/minus.png" alt="Negative" style="width: 15px; height: 15px;">
                        </button>
                    </form>
                    <form action="nuevoComentario.php" method="POST">
                        <input type="hidden" name="idIncidencia" value="{$incidencia['id_incidencia']}">
                        <button type="submit" class="btn btn-primary btn-circle btn-sm">
                            <img src="../img/comment.png" alt="Comment" style="width: 15px; height: 15px;">
                        </button>
                    </form>
                </div>
            </div>
            HTML;
        }
    }

    $db->desconectar();
}

function HTMLContentEnd()
{
    echo <<<HTML
        </div>
    </div>
    HTML;
}

function HTMLUsuarios()
{
    $db = new Conexion();
    $db->conectar();
    $usuarios = $db->getUsuarios();
    $contador = 0;
    foreach ($usuarios as $user) {
        $contador++;
        $clase = $contador % 2 == 0 ? 'row mt-3 beige-bg' : 'row mt-3 grey-bg'; 

        echo <<<HTML
        <link href="../view/vista.css" rel="stylesheet">
        <div class='$clase'>
            <div class='col-md-2'>
                <img src='data:image/jpg;base64,{$user['foto']}' class='img-fluid rounded-circle' style='width: 100px; height: 100px; object-fit: cover;' alt='Foto de perfil'>
            </div>
            <div class='col-md-8'>
                <br>
                <p><strong>Usuario:</strong> {$user['usuario']} <strong>Email:</strong> {$user['email']}<br>
                <strong>Dirección:</strong>   <strong>Teléfono:</strong> <br>
                <strong>Rol:</strong> {$user['rol']} <strong>Estado:</strong> </p>
            </div>
            <div class='col-md-2 d-flex align-items-center justify-content-end'>
                <form action="editarUsuario.php" class="form-signin" style="margin-left: 10px; margin-top: 10px;">
                    <input type="hidden" name="idUsuario" value="{$db->getId($user['usuario'])}">
                    <button type="submit" class="btn btn-lg">
                        <img src="../img/edit_icon.png" alt="Edit" style="width: 30px; height: 30px;">
                    </button>
                </form>
                <form action="borrarUsuario.php" class="form-signin" style="margin-left: 10px; margin-top: 10px;">
                    <input type="hidden" name="idUsuario" value="{$db->getId($user['usuario'])}">
                    <button type="submit" class="btn btn-lg">
                        <img src="../img/delete_icon.png" alt="Delete" style="width: 40px; height: 40px;">
                    </button>
                </form>
            </div>
        </div>
        HTML;
    }
    $db->desconectar();
}



function HTMLMisIncidencias()
{
    echo <<<HTML
        <link href="../view/vista.css" rel="stylesheet">
    HTML;
    $db = new Conexion();
    $db->conectar();
    $user = $_SESSION['user'];
    $id = $db->getId($user);
    $incidencias = $db->getUsuarioIncidencias($id);
    foreach ($incidencias as $incidencia) {
        echo <<<HTML
            <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title">{$incidencia['titulo']}</h5>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <span class="keyword">Lugar:</span> <span class="value">{$incidencia['ubicacion']}</span>
                            <span class="keyword">Fecha:</span> <span class="value">{$incidencia['fecha']}</span>
                            <span class="keyword">Usuario:</span> <span class="value">{$db->getUsuario($incidencia['id_usuario'])}</span>
                        </li>
                        <li class="list-group-item">
                            <span class="keyword">Palabras clave:</span> <span class="value">{$incidencia['palabras_clave']}</span>
                            <span class="keyword">Estado:</span> <span class="value">{$incidencia['estado']}</span>
                            <span class="keyword">Valoraciones:</span> <span class="value">+{$incidencia['val_pos']} -{$incidencia['val_neg']}</span>
                        </li>
                    </ul>
                    <div class="card-body">
                        <p class="card-text">{$incidencia['descripcion']}</p>
                    </div>
        HTML;
    }
    $db->desconectar();
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