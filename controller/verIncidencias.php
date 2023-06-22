<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('../view/funcHTML.php');
require_once('../model/bd.php');

$db = new Conexion();
$db->conectar();

HTMLinicio("Incidencias");
HTMLheader(0);
HTMLnav(0);
HTMLmainContentStart();
HTMLbodyStart();
HTMLbusqueda();
HTMLbodyEnd();
HTMLasideStart();
HTMLaside();
HTMLasideEnd();
HTMLContentStart();
HTMLIncidencias();
HTMLContentEnd();
HTMLfooter();
HTMLfin();

if (!isset($_SESSION['valoraciones'])) {
    $_SESSION['valoraciones'] = [];
}

if (isset($_COOKIE['valoracion_id'])) {
    $valoracion_id = $_COOKIE['valoracion_id'];
    $incidencia_id = $db->extractIncidenciaID($valoracion_id);
    $_SESSION['valoraciones'][$incidencia_id][$valoracion_id] = true;
}

if (isset($_SESSION['message'])) {
    echo '<script>alert("' . $_SESSION['message'] . '");</script>';
    // Borrar el mensaje una vez que se ha mostrado
    unset($_SESSION['message']);
    //session_abort();
}

$db->desconectar();
?>