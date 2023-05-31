<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Procesar los datos del formulario de registro
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $email = $_POST['email'];
    $foto = $_POST['foto'];
    $clave = $_POST['clave'];
    $usuario = $_POST['usuario'];
    $rol = 'colaborador';

    // Aquí puedes realizar la validación de los datos y otras verificaciones necesarias antes de agregar el usuario a la base de datos

    // Realizar la conexión a la base de datos
    require_once('../model/bd.php'); // Archivo que contiene la clase Conexion
    $conexion = new Conexion();
    $conexion->conectar();
    

    // Llamar al método DBaddUsuario() para agregar el usuario a la base de datos
    $idUsuario = $conexion->DBaddUsuario($nombre, $apellidos, $email, $foto, $clave, $usuario, $rol);
    if ($idUsuario) {
        // Usuario registrado exitosamente
        echo 'Usuario registrado con ID: ' . $idUsuario;
        header('Location: ../index.php');
    } else {
        // Error al registrar el usuario
        echo 'Error al registrar el usuario.';
    }

    // Cerrar la conexión a la base de datos
    $conexion->desconectar();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registro de Usuario</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        h2 {
            text-align: center;
        }
        body {
            padding: 20px;
        }
        form {
            max-width: 400px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <h2>Registro de Usuario</h2>
    <form method="POST" action="register.php">
        <div class="form-group">
            <label for="nombre">Nombre:</label>
            <input type="text" class="form-control" name="nombre" required>
        </div>

        <div class="form-group">
            <label for="apellidos">Apellidos:</label>
            <input type="text" class="form-control" name="apellidos" required>
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" name="email" required>
        </div>

        <div class="form-group">
            <label for="foto">Foto:</label>
            <input type="text" class="form-control" name="foto">
        </div>

        <div class="form-group">
            <label for="clave">Contraseña:</label>
            <input type="password" class="form-control" name="clave" required>
        </div>

        <div class="form-group">
            <label for="usuario">Usuario:</label>
            <input type="text" class="form-control" name="usuario" required>
        </div>
        <button type="submit" class="btn btn-primary" formaction="register.php" >Registrar</button>
    </form>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

