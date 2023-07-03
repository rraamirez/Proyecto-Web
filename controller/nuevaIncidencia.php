<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once('../model/bd.php');

if($_SESSION['rol'] != 'admin' && $_SESSION['rol'] != 'colaborador') {
    // No está conectado o no es un administrador, redirigir a página de inicio
    header('Location: ../index.php');
    exit(); 
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['confirm'])) {
        // Procesar los datos del formulario de incidencia
        $titulo = trim(stripslashes(htmlspecialchars($_SESSION['incidencia']['titulo'])));
        $descripcion = trim(stripslashes(htmlspecialchars($_SESSION['incidencia']['descripcion'])));
        $ubicacion = trim(stripslashes(htmlspecialchars($_SESSION['incidencia']['ubicacion'])));
        $palabras_clave = trim(stripslashes(htmlspecialchars($_SESSION['incidencia']['palabras_clave'])));
        $estado = "Pendiente";
        $usuario = trim(stripslashes(htmlspecialchars($_SESSION['user'])));
        $val_pos = 0;
        $val_neg = 0;

        // Realizar la conexión a la base de datos
        $conexion = new Conexion();
        $conexion->conectar();

        // Llamar al método DBaddIncidencia() para agregar la incidencia a la base de datos
        $idIncidencia = $conexion->addIncidencia($usuario, $titulo, $descripcion, $ubicacion, $palabras_clave, $estado, $val_pos, $val_neg);
        $_SESSION['incidencia']['id'] = $idIncidencia;
        if ($idIncidencia) {
            $conexion->addLog($conexion->getId($usuario),  date("Y-m-d H:i:s"), "INFO: El usuario {$usuario} ha creado una nueva incidencia." );
            header('Location: editarIncidencia.php');
        } else {
            // Error al registrar la incidencia
            echo 'Error al registrar la incidencia.';
        }

        $conexion->desconectar();
    } else {
        // Guardar los datos en una sesión y redirigir a la página de confirmación
        $_SESSION['incidencia'] = $_POST;
        header('Location: nuevaIncidencia.php');
    }
} else {
    if(isset($_SESSION['incidencia'])) {
        $titulo = trim(stripslashes(htmlspecialchars($_SESSION['incidencia']['titulo'])));
        $descripcion = trim(stripslashes(htmlspecialchars($_SESSION['incidencia']['descripcion'])));
        $ubicacion = trim(stripslashes(htmlspecialchars($_SESSION['incidencia']['ubicacion'])));
        $palabras_clave = trim(stripslashes(htmlspecialchars($_SESSION['incidencia']['palabras_clave'])));
    }
}
?>


<!DOCTYPE html>
<html>

<head>
    <title>Nueva Incidencia</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        h2 {
            text-align: center;
        }

        body {
            padding: 20px;
        }

        form {
            max-width: 600px;
            margin: 0 auto;
        }
    </style>
</head>

<body>
    <h2>Nueva Incidencia</h2>
    <form method="POST" action="nuevaIncidencia.php">
        <div class="form-group">
            <label for="titulo">Título:</label>
            <input type="text" class="form-control" name="titulo" value="<?= $titulo ?? '' ?>" required <?= isset($titulo) ? 'readonly' : '' ?>>
        </div>

        <div class="form-group">
            <label for="descripcion">Descripción:</label>
            <textarea class="form-control" name="descripcion" required <?= isset($descripcion) ? 'readonly' : '' ?>><?= $descripcion ?? '' ?></textarea>
        </div>

        <div class="form-group">
            <label for="ubicacion">Ubicación:</label>
            <input type="text" class="form-control" name="ubicacion" value="<?= $ubicacion ?? '' ?>" required <?= isset($ubicacion) ? 'readonly' : '' ?>>
        </div>

        <div class="form-group">
            <label for="palabras_clave">Palabras Clave:</label>
            <input type="text" class="form-control" name="palabras_clave" value="<?= $palabras_clave ?? '' ?>" required <?= isset($palabras_clave) ? 'readonly' : '' ?>>
        </div>

        <?php if(isset($titulo)): ?>
            <button type="submit" name="confirm" class="btn btn-primary">Confirmar Incidencia</button>
        <?php else: ?>
            <button type="submit" class="btn btn-primary">Registrar Incidencia</button>
        <?php endif; ?>
    </form>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
