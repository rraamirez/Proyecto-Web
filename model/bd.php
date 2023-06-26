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
    }

    public function desconectar() {
        $this->conn->close();
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

    // Función para obtener las incidencias de un usuario

    function eliminarIncidenciasUsuario($idUsuario) {
        $ids = $this->getIdsPerUser($idUsuario);
        foreach ($ids as $id) {
            $this->eliminarFotoIncidencias($id['id_incidencia']);
        }
        
        $stmt = $this->conn->prepare("DELETE FROM incidencias WHERE id_usuario = ?");
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
    }
        
        
    function eliminarComentariosUsuario($idUsuario) {
        $stmt = $this->conn->prepare("DELETE FROM comentarios WHERE id_usuario = ?");
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
    }

    function eliminarUsuario($idUsuario) {
        $this->eliminarIncidenciasUsuario($idUsuario);
        $this->eliminarComentariosUsuario($idUsuario);
        $stmt = $this->conn->prepare("DELETE FROM usuarios WHERE id = ?");
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
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

    function getTotalIncidencias(){
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM incidencias");
        $stmt->execute();
        $stmt->bind_result($total);
        $stmt->fetch();
        $stmt->close();
        return $total;
    }

    function getIncidenciaMasComentada(){
        $stmt = $this->conn->prepare("SELECT incidencias.id_incidencia, COUNT(comentarios.id_incidencia) AS total FROM incidencias INNER JOIN comentarios ON incidencias.id_incidencia = comentarios.id_incidencia GROUP BY incidencias.id_incidencia ORDER BY total DESC LIMIT 1");
        $stmt->execute();
        $stmt->bind_result($id_incidencia, $total);
        $stmt->fetch();
        $stmt->close();
        $incidencia = array(
            'id_incidencia' => $id_incidencia,
            'total' => $total
        );
        return $incidencia;
    }
    
        
    function getIncidencia($id_incidencia) {
        // Prepara la consulta SQL para actualizar el usuario
        $stmt = $this->conn->prepare("SELECT id_usuario,titulo,descripcion,fecha,ubicacion,estado,palabras_clave FROM incidencias WHERE id_incidencia = ?");
        $stmt->bind_param('i', $id_incidencia);

        // Ejecuta la consulta
        $stmt->execute();
    
        // Obtén el resultado
        $stmt->bind_result($id_usuario, $titulo, $descripcion, $fecha, $ubicacion, $estado, $palabras_clave);
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
                'estado' => $estado,
                'palabras_clave' => $palabras_clave
            );
            return $incidencia;
        } else {
            echo 'Incidencia no encontrada';
            return null;
        }
    }


    function searchIncidencias($numInc, $tipoBusqueda, $lugar, $palabrasClave, $estados) {
        // Preparar la consulta SQL
        $sql = "SELECT id_incidencia, id_usuario, titulo, descripcion, fecha, ubicacion, estado, val_pos, val_neg, palabras_clave FROM incidencias WHERE 1";
        $_SESSION['perPage'] = $numInc;
    
        if (!is_null($lugar)) {
            $sql .= " AND ubicacion = ?";
        }
    
        if (!is_null($palabrasClave)) {
            $sql .= " AND (titulo LIKE ? OR descripcion LIKE ?)";
        }
    
        if (!is_null($estados) && is_array($estados)) {
            $placeholders = str_repeat('?,', count($estados) - 1) . '?';
            $sql .= " AND estado IN ($placeholders)";
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
        if (!is_null($estados) && is_array($estados)) {
            $bind_types .= str_repeat('s', count($estados));
            $bind_values = array_merge($bind_values, $estados);
        }
    
        if ($numInc != 'todas') {
            $bind_types .= 'ii';  // Agregamos dos parámetros enteros
            array_push($bind_values, ($_SESSION['page']-1)*$numInc, $numInc);
            $sql .= " LIMIT ?, ?";
        } else {
            $bind_types .= 'i'; // Añadimos un parámetro entero
            array_push($bind_values, PHP_INT_MAX); // Usamos el número entero más grande posible si queremos todas las incidencias
            $sql .= " LIMIT ?";
        }
    
        // Preparar la sentencia
        $stmt = $this->conn->prepare($sql);
    
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
    
    
    
    function countIncidencias() {
        // Preparar la consulta SQL
        $sql = "SELECT COUNT(*) as total FROM incidencias";
    
        // Preparar la sentencia
        $stmt = $this->conn->prepare($sql);
    
        // Ejecutar la consulta
        $stmt->execute();
    
        // Obtener los resultados
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
    
        // Cerrar la consulta
        $stmt->close();
    
        // Devolver el conteo de incidencias
        return $row['total'];
    }

    function getNombreIncidencia($id_incidencia){
        // Prepara la consulta SQL para obtener el nombre de la incidencia
        $stmt = $this->conn->prepare("SELECT titulo FROM incidencias WHERE id_incidencia = ?");
        $stmt->bind_param('i', $id_incidencia);

        // Ejecuta la consulta
        $stmt->execute();
    
        // Obtén el resultado
        $stmt->bind_result($titulo);
        $stmt->fetch();
    
        // Cierra la consulta
        $stmt->close();
    
        // Comprueba si se encontró la incidencia
        if(isset($titulo)){
            // Crea un array asociativo con los valores
            $incidencia = array(
                'titulo' => $titulo
            );
            return $incidencia;
        } else {
            echo 'Incidencia no encontrada';
            return null;
        }
        
    }

    function editarIncidencia($titulo, $descripcion, $ubicacion, $palabras_clave, $incidencia_id) {
        // Prepara la consulta SQL para actualizar la incidencia
        $stmt = $this->conn->prepare("UPDATE incidencias SET titulo = ?, descripcion = ?, ubicacion = ?, palabras_clave = ? WHERE id_incidencia = ?");
        
        // Asocia los parámetros a las variables
        $stmt->bind_param('ssssi', $titulo, $descripcion, $ubicacion, $palabras_clave, $incidencia_id);
        
        // Ejecuta la consulta
        $result = $stmt->execute();
        
        // Cierra la consulta
        $stmt->close();
        
        // Retorna el resultado de la operación
        return $result;
    }

    function editarIncidenciaAdmin($titulo, $descripcion, $ubicacion, $palabras_clave, $estado, $incidencia_id) {
        // Prepara la consulta SQL para actualizar la incidencia
        $stmt = $this->conn->prepare("UPDATE incidencias SET titulo = ?, descripcion = ?, ubicacion = ?, palabras_clave = ?, estado = ? WHERE id_incidencia = ?");
        
        // Asocia los parámetros a las variables
        $stmt->bind_param('sssssi', $titulo, $descripcion, $ubicacion, $palabras_clave, $estado, $incidencia_id);
        
        // Ejecuta la consulta
        $result = $stmt->execute();
        
        // Cierra la consulta
        $stmt->close();
        
        // Retorna el resultado de la operación
        return $result;
    }
    

    function getUsuarioIncidencias($id_usuario){
        $incidencias = array();
    
        // Prepara la consulta SQL para obtener las incidencias
        $stmt = $this->conn->prepare("SELECT id_usuario, id_incidencia, titulo, descripcion, fecha, ubicacion, estado, palabras_clave, val_pos, val_neg FROM incidencias WHERE id_usuario = ?");
        
        // Asocia los parámetros a las variables
        $stmt->bind_param('i', $id_usuario);
        
        // Ejecuta la consulta
        $stmt->execute();
    
        // Vincula las variables a las columnas del resultado
        $stmt->bind_result($id_usuario, $id_incidencia, $titulo, $descripcion, $fecha, $ubicacion, $estado, $palabras_clave, $val_pos, $val_neg);
    
        // Recorre los resultados
        while ($stmt->fetch()) {
            // Crea un array asociativo con los valores
            $incidencia = array(
                'id_usuario' => $id_usuario,
                'id_incidencia' => $id_incidencia,
                'titulo' => $titulo,
                'descripcion' => $descripcion,
                'fecha' => $fecha,
                'ubicacion' => $ubicacion,
                'estado' => $estado,
                'palabras_clave' => $palabras_clave,
                'val_pos' => $val_pos,
                'val_neg' => $val_neg

            );
            $incidencias[] = $incidencia;
        }
        
        // Cierra la consulta
        $stmt->close();
        
        // Retorna el resultado de la operación
        return $incidencias;
    }


function getIdsPerUser($id_usuario){
        $incidencias = array();
    
        // Prepara la consulta SQL para obtener las incidencias
        $stmt = $this->conn->prepare("SELECT id_incidencia FROM incidencias WHERE id_usuario = ?");
        
        // Asocia los parámetros a las variables
        $stmt->bind_param('i', $id_usuario);
        
        // Ejecuta la consulta
        $stmt->execute();
    
        // Vincula las variables a las columnas del resultado
        $stmt->bind_result($id_incidencia);
    
        // Recorre los resultados
        while ($stmt->fetch()) {
            // Crea un array asociativo con los valores
            $incidencia = array(
                'id_incidencia' => $id_incidencia
            );
            $incidencias[] = $incidencia;
        }
        
        // Cierra la consulta
        $stmt->close();
        
        // Retorna el resultado de la operación
        return $incidencias;
    }

    function eliminarIncidencia($id_incidencia){
        //Borramos las imagenes y comentarios
        $this->eliminarComentariosIncidencia($id_incidencia);
        $this->eliminarImagenesIncidencia($id_incidencia);

        // Preparar la consulta SQL
        $sql = "DELETE FROM incidencias WHERE id_incidencia = ?";
    
        // Preparar la sentencia
        $stmt = $this->conn->prepare($sql);
    
        // Vincular los parámetros
        $stmt->bind_param('i', $id_incidencia);
    
        // Ejecutar la consulta
        $stmt->execute();
    
        // Cerrar la consulta
        $stmt->close();
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
    

    
    function eliminarComentariosIncidencia($id_incidencia){
        // Preparar la consulta SQL
        $sql = "DELETE FROM comentarios WHERE id_incidencia = ?";
    
        // Preparar la sentencia
        $stmt = $this->conn->prepare($sql);
    
        // Vincular los parámetros
        $stmt->bind_param('i', $id_incidencia);
    
        // Ejecutar la consulta
        $stmt->execute();
    
        // Cerrar la consulta
        $stmt->close();
    }


    function eliminarComentario($id){
        $stmt = $this->conn->prepare("DELETE FROM comentarios WHERE id_comentario = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }
    

    ################################################################################################################################
    ################################################################################################################################
        #METODOS VALORACION
    ################################################################################################################################
    ################################################################################################################################

    function addValoracion($incidencia_id, $valoracion) {
        $cookie_name = 'valoraciones_' . $incidencia_id;

        // Verifica si ya existe una cookie para la incidencia específica
        if (isset($_COOKIE[$cookie_name])) {
            return false;
        }

        // Agrega la cookie para la incidencia
        $cookie_value = 'valorado';
        setcookie($cookie_name, $cookie_value, time() + 86400, '/');

        if ($incidencia_id === null || $valoracion === null || !in_array($valoracion, [-1, 0, 1])) {
            return false;
        }
    
        $selectStmt = $this->conn->prepare("SELECT val_pos, val_neg FROM incidencias WHERE id_incidencia = ?");
        $selectStmt->bind_param('i', $incidencia_id);
    
        if (!$selectStmt->execute()) {
            error_log("Error executing SELECT statement: " . $selectStmt->error);
            $selectStmt->close();
            return false;
        }
    
        $selectStmt->bind_result($val_pos, $val_neg);
    
        if ($selectStmt->fetch()) {
            if ($valoracion > 0) {
                $val_pos = intval($val_pos + 1);
            } elseif ($valoracion < 0) {
                $val_neg = intval($val_neg + 1);
            }
    
            $selectStmt->close();
    
            $updateStmt = $this->conn->prepare("UPDATE incidencias SET val_pos = ?, val_neg = ? WHERE id_incidencia = ?");
            $updateStmt->bind_param('iii', $val_pos, $val_neg, $incidencia_id);
    
            if (!$updateStmt->execute()) {
                error_log("Error executing UPDATE statement: " . $updateStmt->error);
                $updateStmt->close();
                return false;
            }
    
            $updateStmt->close();
        } else {
            $selectStmt->close();
            return false;
        }
    
        return true;
    }


    function eliminarValoracionUsuario($idUsuario) {
        $stmt = $this->conn->prepare("DELETE FROM valoraciones WHERE id_usuario = ?");
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
    }
  

    ################################################################################################################################
    ################################################################################################################################
        #METODOS FOTO
    ################################################################################################################################
    ################################################################################################################################

    function addFoto($id_incidencia, $foto){
        // Preparamos la consulta SQL
        $stmt = $this->conn->prepare("INSERT INTO imagenes (id_incidencia, imagen) VALUES (?, ?)");
        
        // Asignamos los parámetros
        $stmt->bind_param('is', $id_incidencia, $foto);
    
        // Ejecutamos la consulta
        if($stmt->execute()) {
            $foto_id = $stmt->insert_id;
        } else {
            $foto_id = false;
        }
    
        // Cerramos la consulta
        $stmt->close();
        
        // Retornamos el resultado
        return $foto_id;
    }

    function searchFotos($id_incidencia) {
        // Preparamos la consulta SQL
        $stmt = $this->conn->prepare("SELECT imagen FROM imagenes WHERE id_incidencia = ?");
        
        // Asignamos los parámetros
        $stmt->bind_param('i', $id_incidencia);
        
        // Ejecutamos la consulta
        $stmt->execute();
        
        // Obtenemos el resultado
        $result = $stmt->get_result();
        
        // Inicializamos un arreglo vacío para almacenar las fotos
        $fotos = array();
        
        // Si hay resultados, los agregamos al arreglo
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $fotos[] = $row['imagen'];
            }
        }
        
        // Cerramos la consulta
        $stmt->close();
        
        // Retornamos el resultado
        return $fotos;
    }
    
    function searchFotosWithID($id_incidencia) {
        // Preparamos la consulta SQL
        $stmt = $this->conn->prepare("SELECT imagen, id_imagen FROM imagenes WHERE id_incidencia = ?");
        
        // Asignamos los parámetros
        $stmt->bind_param('i', $id_incidencia);
        
        // Ejecutamos la consulta
        $stmt->execute();
        
        // Obtenemos el resultado
        $result = $stmt->get_result();
        
        // Inicializamos un arreglo vacío para almacenar las fotos
        $fotos = array();
        
        // Si hay resultados, los agregamos al arreglo
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $fotos[] = array(
                    'id_imagen' => $row['id_imagen'],
                    'imagen' => $row['imagen']
                );
            }
        }
        
        // Cerramos la consulta
        $stmt->close();
        
        // Retornamos el resultado
        return $fotos;
    }

    function eliminarFotoIncidencias($idIncidencia) {
        $stmt = $this->conn->prepare("DELETE FROM imagenes WHERE id_incidencia = ?");
        $stmt->bind_param("i", $idIncidencia);
        $stmt->execute();
        $stmt->close();
    }

    function deleteFoto($id_foto){
        $stmt = $this->conn->prepare("DELETE FROM imagenes WHERE id_imagen = ?");
        $stmt->bind_param("i", $id_foto);
        $stmt->execute();
        $stmt->close();
        
        return true;
    }


    function eliminarImagenesIncidencia($id_incidencia){
        // Preparar la consulta SQL
        $sql = "DELETE FROM imagenes WHERE id_incidencia = ?";
    
        // Preparar la sentencia
        $stmt = $this->conn->prepare($sql);
    
        // Vincular los parámetros
        $stmt->bind_param('i', $id_incidencia);
    
        // Ejecutar la consulta
        $stmt->execute();
    
        // Cerrar la consulta
        $stmt->close();
    }

    ################################################################################################################################
    ################################################################################################################################
        #METODOS LOG
    ################################################################################################################################
    ################################################################################################################################

    function addLog($id_usuario, $fecha, $mensaje){
        // Preparamos la consulta SQL
        $stmt = $this->conn->prepare("INSERT INTO logs (id_usuario, fecha, mensaje) VALUES (?, ?, ?)");
        
        // Asignamos los parámetros
        $stmt->bind_param('iss', $id_usuario, $fecha, $mensaje);
    
        // Ejecutamos la consulta
        if($stmt->execute()) {
            $log_id = $stmt->insert_id;
        } else {
            $log_id = false;
        }
    
        // Cerramos la consulta
        $stmt->close();
        
        // Retornamos el resultado
        return $log_id;
    }

    function getLogs() {
        // Preparamos la consulta SQL
        $stmt = $this->conn->prepare("SELECT fecha, mensaje FROM logs");
        
        // Ejecutamos la consulta
        $stmt->execute();
        
        // Obtenemos el resultado
        $result = $stmt->get_result();
        
        // Inicializamos un arreglo vacío para almacenar los logs
        $logs = array();
        
        // Si hay resultados, los agregamos al arreglo
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $logs[] = $row;
            }
        }
        
        // Cerramos la consulta
        $stmt->close();
        
        // Retornamos el resultado
        return $logs;
    }
    
}

?>