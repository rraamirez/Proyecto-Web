<?php
require_once('dbcredencialesRaul.php');

$backup_file = 'backup.sql';

// Comando para exportar la base de datos
$command = "mysqldump --user=" . DB_USER . " --password=" . DB_PASSWD . " --host=" . DB_HOST . " " . DB_DATABASE . " > {$backup_file}";

// Ejecutar el comando
system($command, $output);

// Comprobar si se ha producido un error
if($output == 0) {
    $_SESSION['message'] = 'Exportación realizada con éxito.';
} else {
    $_SESSION['message'] = 'Ha ocurrido un error durante la exportación.';
}

header("Location: ../index.php");

?>
