<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Procesar los datos del formulario de incidencia
    
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $ubicacion = $_POST['ubicacion'];
    $palabras_clave = $_POST['palabras_clave'];
    $estado = "Pendiente";
    $usuario = $_SESSION['user'];

    // Aquí puedes realizar la validación de los datos y otras verificaciones necesarias antes de agregar la incidencia a la base de datos

    // Realizar la conexión a la base de datos
    require_once('../model/bd.php'); // Archivo que contiene la clase Conexion
    $conexion = new Conexion();
    $conexion->conectar();

    // Llamar al método DBaddIncidencia() para agregar la incidencia a la base de datos
    $idIncidencia = $conexion->addIncidencia($usuario, $titulo, $descripcion, $ubicacion, $palabras_clave, $estado);
    if ($idIncidencia) {
        // Incidencia registrada exitosamente
        echo 'Incidencia registrada con ID: ' . $idIncidencia;
        echo '<script>alert("Incidencia creada correctamente");</script>';
        header('Location: ../index.php');
    } else {
        // Error al registrar la incidencia
        echo 'Error al registrar la incidencia.';
    }

    // Cerrar la conexión a la base de datos
    $conexion->desconectar();
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
            <input type="text" class="form-control" name="titulo" required>
        </div>

        <div class="form-group">
            <label for="descripcion">Descripción:</label>
            <textarea class="form-control" name="descripcion" required></textarea>
        </div>

        <div class="form-group">
            <label for="ubicacion">Ubicación:</label>
            <input type="text" class="form-control" name="ubicacion" required>
        </div>

        <div class="form-group">
            <label for="palabras_clave">Palabras Clave:</label>
            <input type="text" class="form-control" name="palabras_clave" required>
        </div>

        <button type="submit" class="btn btn-primary" formaction="nuevaIncidencia.php">Registrar Incidencia</button>
    </form>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>