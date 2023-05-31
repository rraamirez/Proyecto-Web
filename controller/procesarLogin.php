<?php

require_once('model/bd.php');
// Verificar si se ha enviado el formulario de inicio de sesión
if (isset($_GET['usuario']) && isset($_GET['clave'])) {
    // Obtener los valores del formulario
    $usuario = $_GET['user'];
    $contrasena = $_GET['clave'];

    $db = new Conexion("localhost", "raul", "raul1234", "proyectoTW");
    $db->conectar();
    $db->loginDB($usuario, $contrasena);
    $db->desconectar();
}
?>
