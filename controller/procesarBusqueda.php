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
        $estadoMap = [
            'opcion1' => 'Pendiente',
            'opcion2' => 'Comprobada',
            'opcion3' => 'Tramitada',
            'opcion4' => 'Irresoluble',
            'opcion5' => 'Resuelta',
        ];
        $estado = array_map(function($opcion) use ($estadoMap) {
            return $estadoMap[$opcion] ?? null;
        }, $estado);
        $estado = array_filter($estado);  // Elimina los elementos nulos
    }

    $_SESSION['estado'] = $estado;

}

header('Location: ./verIncidencias.php');

?>