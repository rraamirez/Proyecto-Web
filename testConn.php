<?php
$servername = "localhost"; // Nombre del servidor
$username = "raul"; // Nombre de usuario de la base de datos
$password = "raul1234"; // Contraseña de la base de datos
$dbname = "proyectoTW"; // Nombre de la base de datos

// Crear una conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Crear una tabla
// $sql = "CREATE TABLE ejemplo (
//     id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
//     nombre VARCHAR(30) NOT NULL,
//     apellido VARCHAR(30) NOT NULL,
//     edad INT(3)
// )";

// if ($conn->query($sql) === TRUE) {
//     echo "Tabla 'ejemplo' creada exitosamente";
// } else {
//     echo "Error al crear la tabla: " . $conn->error;
// }

// Insertar valores en la tabla
$sql = "INSERT INTO ejemplo (nombre, apellido, edad)
        VALUES ('Juan', 'Pérez', 25),
               ('María', 'Gómez', 30),
               ('Pedro', 'López', 40)";

if ($conn->query($sql) === TRUE) {
    echo "Valores insertados exitosamente";
} else {
    echo "Error al insertar valores: " . $conn->error;
}

// Cerrar la conexión
$conn->close();
?>
