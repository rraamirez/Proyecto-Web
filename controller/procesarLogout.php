<?php
session_start();
require_once('../model/bd.php');

$conexion = new Conexion();
$conexion->conectar();

$conexion->addLog($conexion->getId(($_SESSION['user'])), date("Y-m-d H:i:s"), "INFO: El usuario {$_SESSION['user']} accede al sistema");

$conexion->desconectar();
session_destroy();
header("Location: ../index.php");

exit();
?>