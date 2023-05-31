<?php

class Conexion {
    private $servername;
    private $username;
    private $password;
    private $dbname;
    private $conn;

    public function __construct() {
        include 'dbcredencialesRaul.php';
        
        $this->servername = DB_HOST;
        $this->username = DB_USER;
        $this->password = DB_PASSWD;
        $this->dbname = DB_DATABASE;
    }

    public function conectar() {
        $this->conn = new mysqli();

        if ($this->conn->connect_error) {
            die("Conexión fallida: " . $this->conn->connect_error);
        }

        echo "Conexión exitosa";
    }

    public function desconectar() {
        $this->conn->close();

        echo "Desconexión exitosa";
    }

    function crearTablaVacia($nombreTabla) {
        $sql = "CREATE TABLE $nombreTabla (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            columna1 VARCHAR(255),
            columna2 INT(11),
            columna3 TEXT
        )";

        if ($this->conn->query($sql) === TRUE) {
            echo "La tabla se creó exitosamente";
        } else {
            echo "Ocurrió un error al crear la tabla";
        }
    }

    function loginDB($email, $passwd) {
        $sql = "SELECT * FROM usuarios WHERE email='$email'";

        $result = $this->conn->query($sql);

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $hashedClave = $row['clave'];

            if (password_verify($passwd, $hashedClave)) {
                echo "El email y la contraseña coinciden.";
            } else {
                echo "La contraseña es incorrecta.";
            }
        } else {
            echo "El email no existe.";
        }
    }

    function DBaddUsuario($nombre, $apellidos, $email, $foto, $clave, $usuario, $rol) {
        $hashedClave = password_hash($clave, PASSWORD_DEFAULT);

        $sql = "INSERT INTO usuarios (nombre, apellidos, email, foto, clave, usuario, rol) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $prep = $this->conn->prepare($sql);
        $prep->bind_param('sssssss', $nombre, $apellidos, $email, $foto, $hashedClave, $usuario, $rol);

        if ($prep->execute()) {
            $id = $prep->insert_id;
            $prep->close();
            return $id;
        } else {
            $prep->close();
            return false;
        }
    }
}


// // Ejemplo de uso
// $conexion = new Conexion("localhost", "raul", "raul1234", "proyectoTW");
// $conexion->conectar();

// // Realiza tus operaciones con la base de datos

// $conexion->desconectar();
?>