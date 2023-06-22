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
    setcookie('id', $idIncidencia, time()+600);
    $usuario = trim(stripslashes(htmlspecialchars($_SESSION['user'])));
    $fecha = date("Y-m-d H:i:s"); // Fecha y hora actual
    // Realizar la conexión a la base de datos
    $conexion = new Conexion();
    $conexion->conectar();
    // Llamar al método DBaddComentario() para agregar el comentario a la base de datos
    if ($conexion->addComentario($idIncidencia, $usuario, $mensaje, $fecha)) {
        // Comentario registrado exitosamente
        echo 'Comentario registrado exitosamente.';
        echo '<script>alert("Comentario creado correctamente");</script>';
        header('Location: verIncidencias.php'); // Redireccionar a verIncidencias.php
    } else {
        // Error al registrar el comentario
        echo 'Error al registrar el comentario.';
    }
    // Cerrar la conexión a la base de datos
    $conexion->desconectar();

} 
?>