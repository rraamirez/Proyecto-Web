<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once('../model/bd.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Procesar los datos del formulario de comentario
    $mensaje = trim(stripslashes(htmlspecialchars($_POST['mensaje'])));
    $idIncidencia = $_SESSION['idIncidencia'];

    $conexion = new Conexion();
    $conexion->conectar();

    if(isset($_SESSION['user']))
        $id_usuario = $conexion->getId(trim(stripslashes(htmlspecialchars($_SESSION['user']))));
    else
        $id_usuario = null;
    
    $fecha = date("Y-m-d H:i:s");
    
    if ($conexion->addComentario($idIncidencia, $id_usuario, $mensaje, $fecha)) {
        if($id_usuario == null)
            $conexion->addLog(($id_usuario), date("Y-m-d H:i:s"), "INFO: El usuario anónimo ha añadido un comentario a la incidencia {$idIncidencia}");
        else
            $conexion->addLog(($id_usuario), date("Y-m-d H:i:s"), "INFO: El usuario {$conexion->getUsuario($id_usuario)} ha añadido un comentario a la incidencia {$idIncidencia}");
        
        header('Location: verIncidencias.php');
    } else {
        // Error al registrar el comentario
        echo $idIncidencia, $id_usuario, $mensaje, $fecha;
        echo 'Error al registrar el comentario.';
    }
    // Cerrar la conexión a la base de datos
    $conexion->desconectar();
} 
?>