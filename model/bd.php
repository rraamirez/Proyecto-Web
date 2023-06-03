<?php

class Conexion {
    private $servername;
    private $username;
    private $password;
    private $dbname;
    private $conn;

    private static $intance = null;

    public function __construct() {
        require_once('dbcredencialesRaul.php');
        
        $this->servername = DB_HOST;
        $this->username = DB_USER;
        $this->password = DB_PASSWD;
        $this->dbname = DB_DATABASE;
    }

    public function conectar() {
        $this->conn = new mysqli($this->servername,$this->username,$this->password,$this->dbname);

        if ($this->conn->connect_error) {
            die("Conexión fallida: " . $this->conn->connect_error);
        }

        echo "Conexión exitosa";
    }

    public function desconectar() {
        $this->conn->close();

        echo "Desconexión exitosa";
    }

    public static function getInstance() {
        if (self::$intance == null) {
          self::$intance = new Conexion();
        }
    
        return self::$intance;
    }

    function usuarioExiste($usuario) {
        // Prepara una consulta SQL para buscar el usuario en la base de datos
        $sql = "SELECT * FROM usuarios WHERE usuario = ?";
        $prep = $this->conn->prepare($sql);
        // Enlaza el valor proporcionado a la consulta SQL
        $prep->bind_param('s', $usuario);
        // Ejecuta la consulta
        $prep->execute();
        // Recoge el resultado
        $resultado = $prep->get_result();
        // Cierra la consulta preparada
        $prep->close();
        // Devuelve si se encontró el usuario
        return $resultado->num_rows > 0;
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

    function logindb($usuario, $contrasena)
    {
        $sql = "SELECT clave FROM usuarios WHERE usuario = ?";
        $prep = $this->conn->prepare($sql);
        $prep->bind_param('s', $usuario);
        $prep->execute();
        $resultado = $prep->get_result();
        if ($resultado->num_rows > 0) {
            $fila = $resultado->fetch_assoc();
            $clave_hash = $fila['clave'];
            if (password_verify($contrasena, $clave_hash)) {
                $prep->close();
                return true;
            } else {
                $prep->close();
                return false;
            }
        } else {
            $prep->close();
            return false;
        }
    }
    

    function DBaddUsuario($nombre, $apellidos, $email, $foto, $clave, $usuario, $rol) {
        $hashedClave = password_hash($clave, PASSWORD_DEFAULT);
    
        $sql = "INSERT INTO usuarios (nombre, apellidos, email, foto, clave, usuario, rol) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $prep = $this->conn->prepare($sql);
        $prep->bind_param('sssssss', $nombre, $apellidos, $email, $foto, $hashedClave, $usuario, $rol);
    
        if ($prep->execute()) {
            $id = $this->conn->insert_id;
            $prep->close();
            return $id;
        } else {
            $prep->close();
            return false;
        }
    }
    

    function getRol($usuario) {
        $sql = "SELECT rol FROM usuarios WHERE usuario = ?";
        $prep = $this->conn->prepare($sql);
        $prep->bind_param('s', $usuario);
        $prep->execute();
        $resultado = $prep->get_result();
        
        // Obtener el valor del resultado
        $row = $resultado->fetch_assoc();
        $rol = $row['rol'];
        
        // Cerrar la conexión y liberar recursos
        $prep->close();
        $this->conn->close();
        
        // Devolver el valor del rol
        return $rol;
    }

    function getImage($usuario) {
        // Prepara la consulta SQL
        $stmt = $this->conn->prepare("SELECT foto FROM usuarios WHERE usuario = ?");
        $stmt->bind_param('s', $usuario);
    
        // Ejecuta la consulta
        $stmt->execute();
    
        // Obtén el resultado
        $stmt->bind_result($foto);
        $stmt->fetch();
    
        // Cierra la conexión
        $stmt->close();
    
        // Retorna los datos de la foto
        if(isset($foto)){
            // Convierte la imagen en una cadena base64
            return $foto;
        }
        else{
            echo 'no foto';
            return null;
        }
    }

    function getId($usuario) {
        // Prepara la consulta SQL
        $stmt = $this->conn->prepare("SELECT id FROM usuarios WHERE usuario = ?");
        $stmt->bind_param('s', $usuario);
    
        // Ejecuta la consulta
        $stmt->execute();
    
        // Obtén el resultado
        $stmt->bind_result($id);
        $stmt->fetch();
    
        // Cierra la consulta
        $stmt->close();
    
        // Retorna el ID del usuario
        if(isset($id)){
            return $id;
        }
        else{
            echo 'Usuario no encontrado';
            return null;
        }
    }
    
    function addIncidencia($usuario, $titulo, $descripcion, $ubicacion, $palabrasClave, $estado) {
        // Primero, obtenemos el id del usuario
        $id_usuario = $this->getId($usuario);
        
        if($id_usuario === null) {
            echo "No se pudo encontrar el usuario.";
            return false;
        }
    
        // Preparamos la consulta SQL
        $stmt = $this->conn->prepare("INSERT INTO incidencias (id_usuario, titulo, descripcion, fecha, ubicacion, estado, palabras_clave) VALUES (?, ?, ?, NOW(), ?, ?, ?)");
        
        // Asignamos los parámetros
        $stmt->bind_param('isssss', $id_usuario, $titulo, $descripcion, $ubicacion, $estado, $palabrasClave);
    
        // Ejecutamos la consulta
        $result = $stmt->execute();
    
        // Verificamos si la consulta fue exitosa
        if($result) {
            echo "Incidencia añadida correctamente.";
        } else {
            echo "Hubo un error al añadir la incidencia.";
        }
    
        // Recuperamos el ID de la incidencia creada
        $incidencia_id = $stmt->insert_id;
        echo "El ID de la incidencia creada es: " . $incidencia_id;
    
        // Cerramos la consulta
        $stmt->close();
    
        // Retornamos el resultado
        return $incidencia_id;
    }
    
    

    function editarUsuario($nombre, $apellidos, $email, $foto, $clave, $usuario) {
        // Prepara la consulta SQL para actualizar el usuario
        $stmt = $this->conn->prepare("UPDATE usuarios SET nombre = ?, apellidos = ?, email = ?, foto = ?, clave = ? WHERE usuario = ?");
    
        // Encripta la clave
        $clave_encriptada = password_hash($clave, PASSWORD_DEFAULT);
    
        // Asocia los parámetros a las variables
        $stmt->bind_param('ssssss', $nombre, $apellidos, $email, $foto, $clave_encriptada, $usuario);
    
        // Ejecuta la consulta
        $result = $stmt->execute();
    
        // Cierra la consulta
        $stmt->close();
    
        // Retorna el resultado de la operación
        return $result;
    }
    
    
    
    
}

?>