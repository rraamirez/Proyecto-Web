<?php
session_start();

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST["user"];
    $contrasena = $_POST["contrasena"];

    require_once('../model/bd.php');

    $conexion = new Conexion();
    $conexion->conectar();
    
    if (!empty($usuario) && !empty($contrasena)) {
        if ($conexion->logindb($usuario, $contrasena)) {
            $_SESSION['user'] = $usuario;
            $_SESSION['rol'] = $conexion->getRol($usuario);
            $_SESSION['message'] = "Login correcto.";
        }
        else
            $_SESSION['message'] = "Error en el login.";
    }else{
        $_SESSION['message'] = "Rellena todos los campos.";

    }

    
    $conexion->desconectar();
   
    // Redireccionar al usuario a index.php
    header("Location: ../index.php");
    exit();
}

?>