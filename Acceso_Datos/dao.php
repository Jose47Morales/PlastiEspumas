<?php
require_once('conexion.php');

class MiDAO
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function mostrarEmpleado($id)
    {
        $sql = "SELECT cargo, identificacion, nombre, huella  FROM empleados WHERE identificacion = :identificacion";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':identificacion', $id, PDO::PARAM_STR);
        $stmt->execute();

        // Obtener todos los resultados en un array asociativo
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Devolver el array de resultados
        return $resultados;

    }

    public function mostrarEmpleadoHuella($id)
    {
        $sql = "SELECT huella FROM empleados WHERE identificacion = :identificacion";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':identificacion', $id, PDO::PARAM_STR);
        $stmt->execute();

        // Obtener la huella si hay resultados
        $huella = $stmt->fetchColumn();

        return $huella;
    }


    public function Registroo($huella)
    {

        $sql = "SELECT identificacion, nombre, date_time, access_type FROM empleados, logs WHERE logs.huella = :huella AND empleados.huella = :huella";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':huella', $huella, PDO::PARAM_INT);
        $stmt->execute();

        // Verificar si la consulta fue exitosa

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }


    public function Registro($huella, $limit, $offset)
    {
        $sql = "SELECT identificacion, nombre, date_time, access_type FROM empleados, logs WHERE logs.huella = :huella AND empleados.huella = :huella LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':huella', $huella, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }
    //SELECT identificacion, nombre, date_time, access_type, photo FROM empleados, logs WHERE logs.huella = 1 AND empleados.huella = 1;


    public function registrarUsuario($username, $password)
    {


        if ($this->verificarExistenciaUsername($username) == "noexiste") {
            return 2;
        } elseif ($this->verificarExistenciaUsername($username) == "existe") {
            $sqlRegistro = "INSERT INTO login (username,password ) VALUES (:username, :password)";
            $stmtRegistro = $this->conn->prepare($sqlRegistro);
            $stmtRegistro->bindParam(':username', $username, PDO::PARAM_STR);
            $stmtRegistro->bindParam(':password', $password, PDO::PARAM_STR);

            if ($stmtRegistro->execute()) {
                return 1;
            } else {
                return 0;
            }
        }

    }

    public function verificarExistenciaUsername($identificacion)
    {
        // Verificar la existencia del username
        $sqlVerificar = "SELECT COUNT(*) FROM empleados WHERE identificacion = :identificacion";
        $stmtVerificar = $this->conn->prepare($sqlVerificar);
        $stmtVerificar->bindParam(':identificacion', $identificacion, PDO::PARAM_STR);
        $stmtVerificar->execute();
        $existeUsuario = $stmtVerificar->fetchColumn();

        if ($existeUsuario > 0) {
            return "existe";
        } else {
            return "noexiste";
        }


    }


    public function verificarCredenciales($usuario, $password)
    {
        try {
            // Verificar las credenciales en la tabla login
            $sqlVerificarCredenciales = "SELECT COUNT(*) FROM login WHERE username = :username AND password = :password";
            $stmtVerificarCredenciales = $this->conn->prepare($sqlVerificarCredenciales);
            $stmtVerificarCredenciales->bindParam(':username', $usuario, PDO::PARAM_STR);
            $stmtVerificarCredenciales->bindParam(':password', $password, PDO::PARAM_STR);
            $stmtVerificarCredenciales->execute();
            $credencialesCorrectas = $stmtVerificarCredenciales->fetchColumn();

            if ($credencialesCorrectas > 0) {
                session_start();
                // Obtener el cargo del usuario desde la tabla empleados
                $sqlObtenerCargo = "SELECT cargo FROM empleados WHERE identificacion = :identificacion";
                $stmtObtenerCargo = $this->conn->prepare($sqlObtenerCargo);
                $stmtObtenerCargo->bindParam(':identificacion', $usuario, PDO::PARAM_STR);
                $stmtObtenerCargo->execute();
                $cargo = $stmtObtenerCargo->fetchColumn();

                $_SESSION['identificacionI'] = $usuario;
                $_SESSION['cargoI'] = $cargo;
                $hella = $this->mostrarEmpleadoHuella($usuario);
                $_SESSION['huellaI'] = $hella;
                return 1;
            } else {
                return 2;
            }
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }

    public function obtenerCargosUnicos()
    {
        $sql = "SELECT DISTINCT cargo FROM empleados;";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        $cargos = $stmt->fetchAll(PDO::FETCH_COLUMN);

        return $cargos;
    }

    public function obtenerCargo($usuario)
    {
        $sql = "SELECT  cargo FROM empleados WHERE identificacion = :identificacion;";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':identificacion', $usuario, PDO::PARAM_STR);
        $stmt->execute();
        $cargos = $stmt->fetchAll(PDO::FETCH_COLUMN);

        return $cargos;
    }


    public function MostrarEmpleadosCargo($cargo, $limit, $offset)
    {
        $sql = "SELECT cargo, identificacion, nombre,huella FROM empleados WHERE cargo = :cargo LIMIT :limit OFFSET :offset; ";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':cargo', $cargo, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }


    public function obtenerRegistrosAntesDe8AM($fecha)
    {
        $sql = "SELECT identificacion,nombre,cargo,access_type,status,date_time FROM logs
            INNER JOIN empleados ON logs.huella = empleados.huella
            WHERE date_trunc('day', logs.date_time) = :fecha
              AND EXTRACT(HOUR FROM logs.date_time) < 8
            ORDER BY logs.date_time";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function obtenerRegistrosDespuesDe8AM($fecha)
    {
        $sql = "SELECT  identificacion,nombre,cargo,access_type,status,date_time  FROM logs
            INNER JOIN empleados ON logs.huella = empleados.huella
            WHERE date_trunc('day', logs.date_time) = :fecha
              AND EXTRACT(HOUR FROM logs.date_time) >= 8
            ORDER BY logs.date_time";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }




    function obtenerFechasEntradaPorFecha($year, $month, $day)
    {
        $query = "SELECT
                l.fecha
              FROM
                empleados e
              JOIN
                logs l ON e.huella = l.huella
              WHERE
                EXTRACT(YEAR FROM l.fecha) = :year AND
                EXTRACT(MONTH FROM l.fecha) = :month AND
                EXTRACT(DAY FROM l.fecha) = :day AND
                l.tipo_acceso = 'Entrada'";

        $stmt = $this->conn->prepare($query);

        try {
            $stmt->execute(array(':year' => $year, ':month' => $month, ':day' => $day));
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $resultados;
        } catch (PDOException $e) {
            die("Error al ejecutar la consulta: " . $e->getMessage());
        }
    }



    public function RegistroPorFecha($year, $month, $day)
    {
        $resultados = $this->obtenerFechasEntradaPorFecha($year, $month, $day);
    }



    public function obtenerDatosAcceso($year, $month, $day)
    {
      
        // Consulta SQL 
        $sql = "
        SELECT
        e.nombre,
        COALESCE(ARRAY_AGG(CASE WHEN l.access_type = 'Entrada' THEN l.date_time END), '{NULL}') AS fechas_entrada,
        COALESCE(ARRAY_AGG(CASE WHEN l.access_type = 'Permiso' THEN l.date_time END), '{NULL}') AS fechas_permiso,
        COALESCE(ARRAY_AGG(CASE WHEN l.access_type = 'Salida' THEN l.date_time END), '{NULL}') AS fechas_salida
    FROM empleados e
    LEFT JOIN logs l ON e.huella = l.huella
    WHERE EXTRACT(YEAR FROM l.date_time) = ? AND EXTRACT(MONTH FROM l.date_time) = ? AND EXTRACT(DAY FROM l.date_time) = ?
    GROUP BY e.nombre;
        ";

        // Preparar la consulta
        $stmt = $this->conn->prepare($sql);

        // Asociar los parámetros
        $stmt->bindParam(1, $year, PDO::PARAM_INT);
        $stmt->bindParam(2, $month, PDO::PARAM_INT);
        $stmt->bindParam(3, $day, PDO::PARAM_INT);

        // Ejecutar la consulta
        $stmt->execute();

        // Obtener los resultados
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Retornar los resultados
        return $resultados;
    }

}
?>
