<?php
require_once('view/funcHTML.php');
require_once('model/bd.php');


$db = new Conexion();
$db->conectar();

HTMLinicio("Inicio");
HTMLheader();

HTMLmainContentStart();
// Aquí va tu contenido principal
HTMLasideStart();
HTMLaside();
HTMLasideEnd();

HTMLfooter();
HTMLfin();



?>