<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('../view/funcHTML.php');
require_once('../model/bd.php');

$db = new Conexion();
$db->conectar();

if (isset($_SESSION['user']) && $db->getRol($_SESSION['user']) === 'admin') {
    HTMLinicio("Mi página");
    HTMLheader(0);
    HTMLnav(0);
    HTMLmainContentStart();
    HTMLbienvenidaStart();
    HTMLbbdd();
    HTMLbienvenidaEnd();
    HTMLasideStart();
    HTMLaside(0);
    HTMWidget1Start();
    HTMLWidget1();
    HTMWidget1End();
    HTMWidget2Start();
    HTMLWidget2();
    HTMWidget2End();
    HTMLasideEnd();
    HTMLfooter();
    HTMLfin();
}else {
    // Si el usuario no es un administrador o no está logueado, redirigir a otra página
    header("Location: ../index.php");
    exit();
}

if (isset($_SESSION['message'])) {
    echo '<script>alert("' . $_SESSION['message'] . '");</script>';
    // Borrar el mensaje una vez que se ha mostrado
    unset($_SESSION['message']);
    //session_abort();
}

?>