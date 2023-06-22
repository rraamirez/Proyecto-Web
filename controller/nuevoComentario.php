<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['idIncidencia'] = trim(stripslashes(htmlspecialchars($_POST['idIncidencia'])));
} 
?>

<!DOCTYPE html>
<html>

<head>
    <title>Nuevo Comentario</title>
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
    <h2>Nuevo Comentario</h2>
    <form method="POST" action="procesarComentario.php">
        <div class="form-group">
            <label for="mensaje">Mensaje:</label>
            <textarea class="form-control" name="mensaje" required></textarea>
        </div>
        <button type="submit" name="confirm" class="btn btn-primary">Registrar Comentario</button>
    </form>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>