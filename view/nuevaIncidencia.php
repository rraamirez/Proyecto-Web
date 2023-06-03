<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Procesar los datos del formulario de registro
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $ubicacion = $_POST['ubicacion'];
    $palabrasClave = $_POST['palabrasClave'];
    $estado = 'Pendiente';

    require_once('../model/bd.php'); // Archivo que contiene la clase Conexion
    $conexion = new Conexion();
    $conexion->conectar();
    //AQUI ES CUANDO SE INSERTAN LOS DATOS EN LA BASE DE DATOS INDICENCIA Y SE OBTIENE EL ID DE LA ULTIMA INCIDENCIA INSERTADA
    // GUARDA EL ID DE LA ULTIMA INCIDENCIA INSERTADA ADDINCIDENCIA
    $idIncidencia = $conexion->addIncidencia($idUsuario, $titulo, $descripcion, $ubicacion, $palabrasClave, $estado);

    //AQUI SE AÑADEN IMAGENES ASOCIANDO A LA ULTIMA INCIDENCIA INSERTADA
    // if (isset($_FILES['imagen'])) {
    //     foreach ($_FILES['imagen']['tmp_name'] as $key => $tmp_name) {
    //         if (is_uploaded_file($tmp_name)) {
    //             $imagen_temp = base64_encode(file_get_contents($tmp_name)); // Preparar la imagen para la inserción
    //         } else {
    //             $defaultPath = "../img/foto_base.png";
    //             if (file_exists($defaultPath)) {
    //                 $imagen_temp = base64_encode(file_get_contents($defaultPath));
    //             } else {
    //                 echo "El archivo de imagen predeterminado no se encuentra en la ruta especificada.";
    //                 $imagen_temp = null;
    //             }
    //         }

    //         // Preparar la consulta SQL para la imagen
    //         $stmt = $conn->prepare("INSERT INTO imagenes (id_incidencia, imagen) VALUES (?, ?)");
    //         $stmt->bind_param("ib", $last_id, $imagen_temp);

    //         // Ejecutar la consulta para la imagen
    //         $stmt->execute();
    //     }
    // }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Incidencia</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: beige;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .form-container {
            background: #fff;
            padding: 2em;
            border-radius: 8px;
            box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 600px;
        }
        .form-group {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1em;
            margin-bottom: 1em;
        }
        label {
            font-weight: bold;
        }
        input[type="text"], textarea, input[type="file"] {
            padding: 0.5em;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            background: #007BFF;
            color: #fff;
            border: none;
            padding: 0.5em 2em;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        
        <div class="form-container">
        <h2 style="text-align: center">Nueva Incidencia:</h2>
            <form action="nuevaIncidencia.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="titulo">Titulo:</label>
                    <input type="text" id="titulo" name="titulo">
                </div>
                <div class="form-group">
                    <label for="descripcion">Descripción:</label>
                    <textarea id="descripcion" name="descripcion"></textarea>
                </div>
                <div class="form-group">
                    <label for="ubicacion">Ubicación:</label>
                    <input type="text" id="ubicacion" name="ubicacion">
                </div>
                <div class="form-group">
                    <label for="palabrasClave">Palabras clave:</label>
                    <input type="text" id="palabrasClave" name="palabrasClave">
                </div>
                <div class="form-group">
                    <label for="imagen">Subir Imagen:</label>
                    <input type="file" id="imagen" name="imagen[]" multiple>
                </div>
                <div class="form-group">
                    <input type="submit" value="Enviar">
                </div>
            </form>
        </div>
    </div>
</body>
</html>
