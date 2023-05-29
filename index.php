<?php
require_once('view/funcHTML.php');
require_once('model/db.php');
require_once('model/bd.php');

$db = new Conexion("localhost", "raul", "raul1234", "proyectoTW");
$db->conectar();
//$db->crearTablaVacia("nueva");
//$db->loginDB("raul@gmail.com", "raulito");

HTMLinicio("Inicio");
HTMLfin();
HTMLheader();

HTMLnav();
HTMLaside();
HTMLfooter();

DB_disconnection($db);
?>