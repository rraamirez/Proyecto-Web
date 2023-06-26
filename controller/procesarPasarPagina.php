<?php
session_start();

if($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['anterior'])){
        $_SESSION['page'] = $_SESSION['page'] -1;
    }
    else if(isset($_POST['siguiente'])){
        $_SESSION['page'] = $_SESSION['page'] +1;
    }
}

header("Location: verIncidencias.php");

exit();
?>