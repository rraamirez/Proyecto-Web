<?php
require_once("bd.php");
function loginDB($user, $contrasena, $db){
    
    $con=$contrasena;

    $res = mysqli_query($db, "SELECT apellido FROM ejemplo WHERE nombre='{$user}'");
    if ($res){
        if (mysqli_num_rows($res)>0){
            $tabla=mysqli_fetch_all($res, MYSQLI_ASSOC);
        }
        else
            $tabla=[];
        mysqli_free_result($res);
    }
    else{
        $tabla=false;
        $salida=false;
    }
    
    if ($tabla!=false){
        if (password_verify($con, $tabla[0]['apellido']))
            $salida=true;
        else 
            $salida=false;
    }
    else 
        $salida=false;
    
    
    
    return $salida;
}

?>