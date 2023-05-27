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
}

// Ejemplo de uso
$conexion = new Conexion("localhost", "raul", "raul1234", "proyectoTW");
$conexion->conectar();

// Realiza tus operaciones con la base de datos

$conexion->desconectar();
?>
