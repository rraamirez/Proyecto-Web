// importar.php
<?php
require_once('dbcredencialesRaul.php');
require_once('bd.php');
$conexion = new Conexion();
$conexion->conectar();

$backup_file = $_FILES['backupFile']['tmp_name'];

// Comando para importar la base de datos
$command = "mysql --user=" . DB_USER . " --password=" . DB_PASSWD . " --host=" . DB_HOST . " " . DB_DATABASE . " < {$backup_file}";

// Ejecutar el comando
system($command, $output);

// Comprobar si se ha producido un error
if($output == 0) {
    $_SESSION['message'] = 'Importación realizada con éxito.';
    $conexion->addLog(null, date("Y-m-d H:i:s"), "INFO: Se ha restaurado la BBDD desde un fichero externo");
} else {
    $_SESSION['message'] = 'Ha ocurrido un error durante la importación.';
}

$conexion->desconectar();

header("Location: ../index.php");

?>
