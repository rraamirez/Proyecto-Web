<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once('../model/bd.php');
session_start();

$editMode = true;
$usuario = null;

$conexion = new Conexion();
$conexion->conectar();

$idUser = (isset($_GET['id'])) ? $_GET['id'] : -1;
if($idUser != -1){
    $usuario = $conexion->getUsuario($idUser);
    $usuario = $conexion->getUserData($usuario);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Comprueba si se ha cargado un archivo
    $foto = (is_uploaded_file($_FILES['foto']['tmp_name'])) ? base64_encode(file_get_contents($_FILES['foto']['tmp_name'])) : $usuario['foto'];

    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $email = $_POST['email'];
    $clave = $_POST['clave'];

    $idUsuario = $conexion->editarUsuario($nombre, $apellidos, $email, $foto, $clave, $usuario['usuario']);

    if ($idUsuario) {
        // Usuario editado exitosamente
        echo 'Usuario editado con ID: ' . $idUsuario;
        echo '<script>alert("Usuario editado correctamente.");</script>';
        header('Location: verUsuarios.php'); 
    } else {
        // Error al editar el usuario
        echo 'Error al editar el usuario.';
    }

    // Cerrar la conexión a la base de datos
    $conexion->desconectar();
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
    <form method="POST" action="editarUsuarioAdmin.php?id=<?php echo $idUser?>" enctype="multipart/form-data">
        <div class="form-group">
            <label for="nombre">Nombre:</label>
            <input type="text" class="form-control" name="nombre" value="<?php echo $usuario['nombre']?>" <?php echo $editMode ? '' : 'readonly' ?>>
        </div>

        <div class="form-group">
            <label for="apellidos">Apellidos:</label>
            <input type="text" class="form-control" name="apellidos" value="<?php echo htmlspecialchars($usuario['apellidos'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" <?php echo $editMode ? '' : 'readonly' ?>>
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($usuario['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" <?php echo $editMode ? '' : 'readonly' ?>>
        </div>

        <div class="form-group">
            <label for="foto">Foto:</label>
            <input type="file" class="form-control" name="foto" <?php echo $editMode ? '' : 'disabled' ?>>
        </div>

        <div class="form-group">
            <label for="clave">Contraseña:</label>
            <input type="password" class="form-control" name="clave" value="<?php echo htmlspecialchars($usuario['clave'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" <?php echo $editMode ? '' : 'readonly' ?> required>
        </div>

        <button type="submit" class="btn btn-primary" name="<?php echo $editMode ? 'edit' : 'confirm' ?>" formaction="editarUsuarioAdmin.php?id=<?php echo $idUser?>"><?php echo $editMode ? 'Editar' : 'Confirmar' ?></button>
    </form>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
