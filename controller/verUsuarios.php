<?php
require_once('../view/funcHTML.php');
require_once('../model/bd.php');

$db = new Conexion();
$db->conectar();

// Verificar si el usuario está logueado y es un administrador
if (isset($_SESSION['user']) && $db->getRol($_SESSION['user']) === 'admin') {
    HTMLinicio("Mi página");
    HTMLheader(0);
    HTMLnav(0);
    HTMLmainContentStart();
    HTMLbienvenidaStart();
    HTMLUsuarios();
    HTMLbienvenidaEnd();
    HTMLasideStart();
    HTMLaside();
    HTMLasideEnd();
    HTMLfooter();
    HTMLfin();

    if (isset($_SESSION['message'])) {
        echo '<script>alert("' . $_SESSION['message'] . '");</script>';
        // Borrar el mensaje una vez que se ha mostrado
        unset($_SESSION['message']);
    }
} else {
    // Si el usuario no es un administrador o no está logueado, redirigir a otra página
    header("Location: ../index.php");
    exit();
}

$db->desconectar();

?>