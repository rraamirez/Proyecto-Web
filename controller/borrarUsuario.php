<?php
  session_start();  
  require_once ("../model/bd.php");

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['idUsuario'];

    $conexion = new Conexion();
    $conexion->conectar();
    $conexion->eliminarUsuario($id);
    $conexion->desconectar();

    header("Location: verUsuarios.php");

    exit();

  }

   

?>