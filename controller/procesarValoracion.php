<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once('../model/bd.php');


if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Extraer los datos del formulario
    $idIncidencia = $_POST['idIncidencia'];
    $valoracion = $_POST['valoracion'];

    $conexion = new Conexion();
    $conexion->conectar();
    
    
    if ($conexion->addValoracion($idIncidencia, $valoracion)) {
    } else {
    }
    
    $conexion->desconectar();
}

header('Location: ./verIncidencias.php');

?>