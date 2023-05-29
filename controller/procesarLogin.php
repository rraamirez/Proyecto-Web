<?php

require_once('model/bd.php');
// Verificar si se ha enviado el formulario de inicio de sesión
if (isset($_GET['user']) && isset($_GET['contrasena'])) {
    // Obtener los valores del formulario
    $usuario = $_GET['user'];
    $contrasena = $_GET['contrasena'];

    $db = new Conexion("localhost", "raul", "raul1234", "proyectoTW");
    $db->conectar();
    $db->loginDB($usuario, $contrasena);
    DB_disconnection($db);
}
?>
