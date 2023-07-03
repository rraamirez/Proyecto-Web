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
    
    if(isset($_SESSION['user']))
        $id_usuario = $conexion->getId(trim(stripslashes(htmlspecialchars($_SESSION['user']))));
    else
        $id_usuario = null;
    
    if ($conexion->addValoracion($idIncidencia, $valoracion)) {
        if($id_usuario == null)
            $conexion->addLog($id_usuario, date("Y-m-d H:i:s"), "INFO: El usuario anónimo accede al sistema");
        else
            $conexion->addLog($id_usuario, date("Y-m-d H:i:s"), "INFO: El usuario {$_SESSION['user']} ha valorado la incidencia {$idIncidencia}");
    } else {
    }
    
    $conexion->desconectar();
}

header('Location: verIncidencias.php');

?>