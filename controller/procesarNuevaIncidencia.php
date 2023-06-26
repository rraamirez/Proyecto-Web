<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once('../model/bd.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['confirm'])) {
        // Procesar los datos del formulario de incidencia
        $titulo = trim(stripslashes(htmlspecialchars($_SESSION['incidencia']['titulo'])));
        $descripcion = trim(stripslashes(htmlspecialchars($_SESSION['incidencia']['descripcion'])));
        $ubicacion = trim(stripslashes(htmlspecialchars($_SESSION['incidencia']['ubicacion'])));
        $palabras_clave = trim(stripslashes(htmlspecialchars($_SESSION['incidencia']['palabras_clave'])));
        $estado = "Pendiente";
        $usuario = trim(stripslashes(htmlspecialchars($_SESSION['user'])));
        $val_pos = 0;
        $val_neg = 0;

        // Realizar la conexión a la base de datos
        $conexion = new Conexion();
        $conexion->conectar();

        // Llamar al método DBaddIncidencia() para agregar la incidencia a la base de datos
        $idIncidencia = $conexion->addIncidencia($usuario, $titulo, $descripcion, $ubicacion, $palabras_clave, $estado, $val_pos, $val_neg);
        $_SESSION['incidencia']['id'] = $idIncidencia;
        if ($idIncidencia) {
            
            header('Location: editarIncidencia.php');
        } else {
            // Error al registrar la incidencia
            echo 'Error al registrar la incidencia.';
        }

        $conexion->desconectar();
    } else {
        // Guardar los datos en una sesión y redirigir a la página de confirmación
        $_SESSION['incidencia'] = $_POST;
        header('Location: nuevaIncidencia.php');
    }
} else {
    if(isset($_SESSION['incidencia'])) {
        $titulo = trim(stripslashes(htmlspecialchars($_SESSION['incidencia']['titulo'])));
        $descripcion = trim(stripslashes(htmlspecialchars($_SESSION['incidencia']['descripcion'])));
        $ubicacion = trim(stripslashes(htmlspecialchars($_SESSION['incidencia']['ubicacion'])));
        $palabras_clave = trim(stripslashes(htmlspecialchars($_SESSION['incidencia']['palabras_clave'])));
    }
}
?>