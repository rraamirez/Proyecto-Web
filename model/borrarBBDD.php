<?php
//BORRA LA BASE DE DATOS POR COMPLETO

require_once('dbcredencialesRaul.php');

$db = new mysqli(DB_HOST, DB_USER, DB_PASSWD, DB_DATABASE);

if ($db->connect_error) {
    die('Error de Conexión (' . $db->connect_errno . ') '. $db->connect_error);
}

// Borrar la base de datos
if ($db->query("DROP DATABASE IF EXISTS ".DB_DATABASE)) {
    $_SESSION['message'] = 'La base de datos ha sido borrada.';
    $conexion->addLog(null, date("Y-m-d H:i:s"), "INFO: Se ha borrado la BBDD con éxito");
} else {
    $_SESSION['message'] = 'Ha ocurrido un error durante el borrado.';
}

$db->close();

header('Location: ../index.php');
?>
