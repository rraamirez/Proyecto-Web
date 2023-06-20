<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Extraer los datos del formulario
    $_SESSION['ordenarPor'] = isset($_POST['ordenarPor']) && !empty($_POST['ordenarPor']) ? $_POST['ordenarPor'] : null;
    $_SESSION['lugar'] = isset($_POST['lugar']) && !empty($_POST['lugar']) ? $_POST['lugar'] : null;
    $_SESSION['textoBusqueda'] = isset($_POST['textoBusqueda']) && !empty($_POST['textoBusqueda']) ? $_POST['textoBusqueda'] : null;
    $_SESSION['incidenciasPorPagina'] = isset($_POST['incidenciasPorPagina']) ? $_POST['incidenciasPorPagina'] : 'todas';

    // El campo 'estado' es un array, por lo que hay que verificar cada una de sus opciones
    $estado = isset($_POST['estado']) ? $_POST['estado'] : null;
    if ($estado) {
        $opcion1 = in_array('opcion1', $estado);
        $opcion2 = in_array('opcion2', $estado);
        $opcion3 = in_array('opcion3', $estado);
        $opcion4 = in_array('opcion4', $estado);
        $opcion5 = in_array('opcion5', $estado);
    } else {
        $opcion1 = $opcion2 = $opcion3 = $opcion4 = $opcion5 = false;
    }

    //$_SESSION['estado'] = $estado;

    $_SESSION['estado'] = "Pendiente";
}

header('Location: ./verIncidencias.php');

?>