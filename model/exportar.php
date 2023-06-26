<?php
require_once('dbcredencialesRaul.php');
require_once('bd.php');
$conexion = new Conexion();
$conexion->conectar();

$backup_file = 'backup.sql';

// Comando para exportar la base de datos
$command = "mysqldump --user=" . DB_USER . " --password=" . DB_PASSWD . " --host=" . DB_HOST . " " . DB_DATABASE . " > {$backup_file}";

// Ejecutar el comando
system($command, $output);

// Comprobar si se ha producido un error
if($output == 0) {
    $_SESSION['message'] = 'Exportación realizada con éxito.';
    $conexion->addLog(null, date("Y-m-d H:i:s"), "INFO: Se ha importado la BBDD a un fichero externo");
} else {
    $_SESSION['message'] = 'Ha ocurrido un error durante la exportación.';
}

$conexion->desconectar();

header("Location: ../index.php");

?>
