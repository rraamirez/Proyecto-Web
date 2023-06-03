<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

$editMode = true;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['confirm'])) {
        // Confirmación de la edición del usuario
        // Realizar la conexión a la base de datos
        require_once('../model/bd.php'); // Archivo que contiene la clase Conexion
        $conexion = new Conexion();
        $conexion->conectar();

        // Llamar al método editarUsuario() para actualizar los datos del usuario en la base de datos
        $idUsuario = $conexion->editarUsuario($_SESSION['nombre'], $_SESSION['apellidos'], $_SESSION['email'], $_SESSION['foto'], $_SESSION['clave'], $_SESSION['user']);
        if ($idUsuario) {
            // Usuario editado exitosamente
            echo 'Usuario editado con ID: ' . $idUsuario;
            echo '<script>alert("Usuario editado correctamente.");</script>';
            header('Location: ../index.php');
        } else {
            // Error al editar el usuario
            echo 'Error al editar el usuario.';
        }

        // Cerrar la conexión a la base de datos
        $conexion->desconectar();
    } else {
        // Procesar los datos del formulario de edición
        $_SESSION['nombre'] = $_POST['nombre'];
        $_SESSION['apellidos'] = $_POST['apellidos'];
        $_SESSION['email'] = $_POST['email'];
        // Comprueba si se ha cargado un archivo
        if (is_uploaded_file($_FILES['foto']['tmp_name'])) {
            // Lee el contenido del archivo y lo convierte en un string binario
            $_SESSION['foto'] = base64_encode(file_get_contents($_FILES['foto']['tmp_name']));
        } else {
            require_once('../model/bd.php');
            $db = new Conexion();
            $db->conectar();
            $_SESSION['foto'] = $db->getImage($_SESSION['user']);
            $db->desconectar();
        }
        $_SESSION['clave'] = $_POST['clave'];
        $editMode = false;
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Editar Usuario</title>
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
    <h2><?php echo $editMode ? 'Editar Usuario' : 'Confirmar Edición' ?></h2>
    <form method="POST" action="editarUsuario.php" enctype="multipart/form-data">
        <div class="form-group">
            <label for="nombre">Nombre:</label>
            <input type="text" class="form-control" name="nombre" value="<?php echo isset($_SESSION['nombre']) ? $_SESSION['nombre'] : '' ?>" <?php echo $editMode ? '' : 'readonly' ?>>
        </div>

        <div class="form-group">
            <label for="apellidos">Apellidos:</label>
            <input type="text" class="form-control" name="apellidos" value="<?php echo isset($_SESSION['apellidos']) ? $_SESSION['apellidos'] : '' ?>" <?php echo $editMode ? '' : 'readonly' ?>>
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" name="email" value="<?php echo isset($_SESSION['email']) ? $_SESSION['email'] : '' ?>" <?php echo $editMode ? '' : 'readonly' ?>>
        </div>

        <div class="form-group">
            <label for="foto">Foto:</label>
            <input type="file" class="form-control" name="foto" <?php echo $editMode ? '' : 'disabled' ?>>
        </div>

        <div class="form-group">
            <label for="clave">Contraseña:</label>
            <input type="password" class="form-control" name="clave" value="<?php echo isset($_SESSION['clave']) ? $_SESSION['clave'] : '' ?>" <?php echo $editMode ? '' : 'readonly' ?>>
        </div>

        <button type="submit" class="btn btn-primary" name="<?php echo $editMode ? 'edit' : 'confirm' ?>" formaction="editarUsuario.php"><?php echo $editMode ? 'Editar' : 'Confirmar' ?></button>
    </form>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
