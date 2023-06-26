<?php
session_start();

if($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['direccion'])){
        if($_POST['direccion'] == "anterior"){
            $_SESSION['page'] = $_SESSION['page'] -1;
        }
        else if($_POST['direccion'] == "siguiente"){
            $_SESSION['page'] = $_SESSION['page'] +1;
        }
    }
}

header("Location: verIncidencias.php");

exit();
?>