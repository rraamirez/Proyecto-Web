<?php
  session_start();  

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['idIncidencia'];

    require_once ("../model/bd.php");
    $conexion = new Conexion();
    $conexion->conectar();
    $conexion->eliminarIncidencia($id);
    $conexion->desconectar();

    $loc = $_POST['localizacion'];

    header("Location: $loc");

    exit();
  }
?>