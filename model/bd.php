<?php
require_once('dbcredencialesRaul.php');
class Conexion {
    private $servername;
    private $username;
    private $password;
    private $dbname;
    private $conn;

    private static $intance = null;

    ################################################################################################################################
    ################################################################################################################################
        #METODOS CLASE CONEXION
    ################################################################################################################################
    ################################################################################################################################

    public function __construct() {
        
        
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

    ################################################################################################################################
    ################################################################################################################################
        #METODOS USUARIO
    ################################################################################################################################
    ################################################################################################################################
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
            echo 'no user';
            return "21";
        }
    }

    function getUsuario($id) {
        // Prepara la consulta SQL
        $stmt = $this->conn->prepare("SELECT usuario FROM usuarios WHERE id = ?");
        $stmt->bind_param('i', $id);
    
        // Ejecuta la consulta
        $stmt->execute();
    
        // Obtén el resultado
        $stmt->bind_result($usuario);
        $stmt->fetch();
    
        // Cierra la consulta
        $stmt->close();
    
        // Retorna el usuario
        if (isset($usuario)) {
            return $usuario;
        } else {
            return "No se encontró el usuario";
        }
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

    function getUsuarios() {
        // Prepara la consulta SQL para obtener los usuarios
        $stmt = $this->conn->prepare("SELECT id, nombre, apellidos, email, foto, clave, usuario, rol FROM usuarios ORDER BY id DESC");
    
        // Ejecuta la consulta
        $stmt->execute();
    
        // Vincula las variables a las columnas del resultado
        $stmt->bind_result($id, $nombre, $apellidos, $email, $foto, $clave, $usuario, $rol);
    
        // Crea un array para almacenar todos los usuarios
        $usuarios = array();
    
        // Recorre los resultados y añade cada usuario al array
        while ($stmt->fetch()) {
            $usuarios[] = array(
                'id' => $id,
                'nombre' => $nombre,
                'apellidos' => $apellidos,
                'email' => $email,
                'foto' => $foto,
                'clave' => $clave,
                'usuario' => $usuario,
                'rol' => $rol
            );
        }
    
        // Cierra la consulta
        $stmt->close();
    
        // Comprueba si se encontró algún usuario
        if (!empty($usuarios)){
            return $usuarios;
        } else {
            return null;
        }
    }

    function getUserData($usuario) {
        // Prepara la consulta SQL
        $stmt = $this->conn->prepare("SELECT id, nombre, apellidos, email, foto, usuario, rol FROM usuarios WHERE usuario = ?");
        $stmt->bind_param('s', $usuario);
    
        // Ejecuta la consulta
        $stmt->execute();
    
        // Vincula las variables a las columnas del resultado
        $stmt->bind_result($id, $nombre, $apellidos, $email, $foto, $usuario, $rol);
    
        // Inicializa el array del usuario
        $usuarioData = array();
    
        // Recoge el resultado
        if ($stmt->fetch()) {
            $usuarioData = array(
                'id' => $id,
                'nombre' => $nombre,
                'apellidos' => $apellidos,
                'email' => $email,
                'foto' => $foto,
                'usuario' => $usuario,
                'rol' => $rol
            );
        }
    
        // Cierra la consulta
        $stmt->close();
    
        // Retorna los datos del usuario
        return $usuarioData;
    }
    
    
    ################################################################################################################################
    ################################################################################################################################
        #METODOS BASE DE DATOS
    ################################################################################################################################
    ################################################################################################################################

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

    ################################################################################################################################
    ################################################################################################################################
        #METODOS INCIDENCIA
    ################################################################################################################################
    ################################################################################################################################
    
    function addIncidencia($usuario, $titulo, $descripcion, $ubicacion, $palabrasClave, $estado, $val_pos, $val_neg) {
        // Primero, obtenemos el id del usuario
        $id_usuario = $this->getId($usuario);
        
        if($id_usuario === null) {
            return false;
        }

        // Preparamos la consulta SQL
        $stmt = $this->conn->prepare("INSERT INTO incidencias (id_usuario, titulo, descripcion, fecha, ubicacion, estado, palabras_clave, val_pos, val_neg) VALUES (?, ?, ?, NOW(), ?, ?, ?, ?, ?)");
        
        // Aseguramos que $estado es una cadena
        $estado = (string)$estado;
        
        // Asignamos los parámetros
        $stmt->bind_param('isssssii', $id_usuario, $titulo, $descripcion, $ubicacion, $estado, $palabrasClave, $val_pos, $val_neg);

        // Ejecutamos la consulta
        if($stmt->execute()) {
            $incidencia_id = $stmt->insert_id;
        } else {
            $incidencia_id = false;
        }

        // Cerramos la consulta
        $stmt->close();
        
        // Retornamos el resultado
        return $incidencia_id;
    }

        
    /*function getIncidencia($id_incidencia) {
        // Prepara la consulta SQL para actualizar el usuario
        $stmt = $this->conn->prepare("SELECT id_usuario,titulo,descripcion,fecha,ubicacion,estado FROM incidencias WHERE id_incidencia = ?");
        $stmt->bind_param('i', $id_incidencia);

        // Ejecuta la consulta
        $stmt->execute();
    
        // Obtén el resultado
        $stmt->bind_result($id_usuario, $titulo, $descripcion, $fecha, $ubicacion, $estado);
        $stmt->fetch();
    
        // Cierra la consulta
        $stmt->close();
    
        // Comprueba si se encontró la incidencia
        if(isset($id_usuario)){
            // Crea un array asociativo con los valores
            $incidencia = array(
                'id_usuario' => $id_usuario,
                'titulo' => $titulo,
                'descripcion' => $descripcion,
                'fecha' => $fecha,
                'ubicacion' => $ubicacion,
                'estado' => $estado
            );
            return $incidencia;
        } else {
            echo 'Incidencia no encontrada';
            return null;
        }
    }*/


    function searchIncidencias($numInc, $tipoBusqueda, $lugar, $palabrasClave, $estado) {
        // Preparar la consulta SQL
        $sql = "SELECT id_incidencia, id_usuario, titulo, descripcion, fecha, ubicacion, estado, val_pos, val_neg, palabras_clave FROM incidencias WHERE 1";
    
        if (!is_null($lugar)) {
            $sql .= " AND ubicacion = ?";
        }
    
        if (!is_null($palabrasClave)) {
            $sql .= " AND (titulo LIKE ? OR descripcion LIKE ?)";
        }
    
        if (!is_null($estado)) {
            $sql .= " AND estado = ?";
        }
    
        switch ($tipoBusqueda) {
            case 0:
                $sql .= " ORDER BY fecha DESC";
                break;
            case 1:
                $sql .= " ORDER BY val_pos DESC, val_neg ASC"; // Ordenar primero por valoraciones positivas y luego por negativas
                break;
            default:
                // Por defecto ordenará por fecha
                $sql .= " ORDER BY fecha DESC";
                break;
        }
    
        if ($numInc != 'todas') {
            $sql .= " LIMIT ?";
        }
    
        // Preparar la sentencia
        $stmt = $this->conn->prepare($sql);
    
        // Ligando los parámetros
        $bind_types = '';
        $bind_values = [];
    
        if (!is_null($lugar)) {
            $bind_types .= 's';
            array_push($bind_values, $lugar);
        }
        if (!is_null($palabrasClave)) {
            $bind_types .= 'ss';
            array_push($bind_values, "%$palabrasClave%", "%$palabrasClave%");
        }
        if (!is_null($estado)) {
            $bind_types .= 's';
            array_push($bind_values, $estado);
        }
        if ($numInc != 'todas') {
            $bind_types .= 'i';
            array_push($bind_values, $numInc);
        }
        $stmt->bind_param($bind_types, ...$bind_values);
    
        // Ejecutar la consulta
        $stmt->execute();
    
        // Obtener los resultados
        $result = $stmt->get_result();
        $incidencias = $result->fetch_all(MYSQLI_ASSOC);
    
        // Cerrar la consulta
        $stmt->close();
    
        return $incidencias;
    }
    

    ################################################################################################################################
    ################################################################################################################################
        #METODOS COMENTARIO
    ################################################################################################################################
    ################################################################################################################################
    
    function addComentario($idIncidencia, $id_usuario, $mensaje, $fecha) {
        // Preparamos la consulta SQL
        $stmt = $this->conn->prepare("INSERT INTO comentarios (id_incidencia, id_usuario, mensaje, fecha) VALUES (?, ?, ?, ?)");
        
        // Asignamos los parámetros
        $stmt->bind_param('iiss', $idIncidencia, $id_usuario, $mensaje, $fecha);
    
        // Ejecutamos la consulta
        if($stmt->execute()) {
            $comentario_id = $stmt->insert_id;
        } else {
            $comentario_id = false;
        }
    
        // Cerramos la consulta
        $stmt->close();
        
        // Retornamos el resultado
        return $comentario_id;
    }

    function searchComentarios($idIncidencia) {
        // Preparar la consulta SQL
        $sql = "SELECT id_comentario, id_incidencia, id_usuario, mensaje, fecha FROM comentarios WHERE id_incidencia = ? ORDER BY fecha DESC";
    
        // Preparar la sentencia
        $stmt = $this->conn->prepare($sql);
    
        // Ligando los parámetros
        $stmt->bind_param('i', $idIncidencia);
    
        // Ejecutar la consulta
        $stmt->execute();
    
        // Obtener los resultados
        $result = $stmt->get_result();
        $comentarios = $result->fetch_all(MYSQLI_ASSOC);
    
        // Cerrar la consulta
        $stmt->close();
    
        return $comentarios;
    }    
    

    ################################################################################################################################
    ################################################################################################################################
        #METODOS VALORACION
    ################################################################################################################################
    ################################################################################################################################

    function addValoracion($incidencia_id, $valoracion) {
        if($incidencia_id === null || $valoracion === null || !in_array($valoracion, [-1, 0, 1])) {
            return false;
        }
    
        $stmt = $this->conn->prepare("SELECT val_pos, val_neg FROM incidencias WHERE id_incidencia = ?");
        $stmt->bind_param('i', $incidencia_id);
        
        if(!$stmt->execute()) {
            error_log("Error executing SELECT statement: " . $stmt->error);
            $stmt->close();
            return false;
        }
    
        $stmt->bind_result($val_pos, $val_neg);
        
        if ($stmt->fetch()) {
            if ($valoracion > 0) {
                $val_pos = intval($val_pos + 1);
            } elseif ($valoracion < 0) {
                $val_neg = intval($val_neg + 1);
            }
    
            $updateStmt = $this->conn->prepare("UPDATE incidencias SET val_pos = ?, val_neg = ? WHERE id_incidencia = ?");
            $updateStmt->bind_param('iii', $val_pos, $val_neg, $incidencia_id);
            
            if(!$updateStmt->execute()) {
                error_log("Error executing UPDATE statement: " . $updateStmt->error);
                $updateStmt->close();
                $stmt->close();
                return false;
            }
            
            $updateStmt->close();
        } else {
            $stmt->close();
            return false;
        }
        
        $stmt->close();
        
        return true;
    }
}
?>