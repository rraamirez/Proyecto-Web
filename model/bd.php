<?php
class Conexion {
    private $servername;
    private $username;
    private $password;
    private $dbname;
    private $conn;
    
    public function __construct($servername, $username, $password, $dbname) {
        $this->servername = $servername;
        $this->username = $username;
        $this->password = $password;
        $this->dbname = $dbname;
    }
    
    public function conectar() {
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        
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
        $sql = "SELECT * FROM usuarios WHERE email='$email' AND password='$passwd'";

        $result = $this->conn->query($sql);

        if ($result->num_rows == 1) {
            echo "El email y la contraseña coinciden.";
        } else {
            echo "El email y la contraseña no coinciden.";
        }

    }
}

// // Ejemplo de uso
// $conexion = new Conexion("localhost", "raul", "raul1234", "proyectoTW");
// $conexion->conectar();

// // Realiza tus operaciones con la base de datos

// $conexion->desconectar();
?>
