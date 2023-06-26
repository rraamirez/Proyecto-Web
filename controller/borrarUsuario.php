<?php
  session_start();  

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['idUsuario'];

    require_once ("../model/bd.php");
    $conexion = new Conexion();
    $conexion->conectar();
    $conexion->eliminarUsuario($id);
    $conexion->desconectar();

    header("Location: verUsuarios.php");

    exit();

  }

   

?>