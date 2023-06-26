<?php
  session_start();  

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id_comentario'];

    require_once ("../model/bd.php");
    $conexion = new Conexion();
    $conexion->conectar();
    $conexion->eliminarComentario($id);
    $conexion->desconectar();

    header("Location: " . $_SERVER['HTTP_REFERER']);

    exit();
  }
?>