<?php
require_once('view/funcHTML.php');
require_once('model/db.php');
require_once('model/bd.php');

// $db = DB_connection();

// crearTablaVacia($db, "ejemplo");

$db = new Conexion("localhost", "raul", "raul1234", "proyectoTW");
$db->conectar();
$db->crearTablaVacia("nueva");

HTMLinicio("Inicio");
HTMLfin();
HTMLheader();

HTMLnav();
HTMLaside();
HTMLfooter();

DB_disconnection($db);
?>