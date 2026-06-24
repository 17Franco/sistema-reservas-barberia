<?php
    namespace Barberia\Backend\infraestructura\persistencia;

    use Exception;
    use mysqli;
    use mysqli_sql_exception;

    require_once __DIR__ . '/../../infraestructura/config/ParametrosConexion.php';

    class Conectar{
        private string $host = SERVIDOR;
        private string $user = USUARIO;
        private string $pass = CONTRASEÑA;
        private string $bd = BASEDATOS;
        private mysqli $conecto;

        public function conectar(): mysqli{
        try{
            //haciendo uso del constructor de la clase mysqli
            $conexion = new mysqli($this->host,$this->user,$this->pass,$this->bd);

            $conexion->set_charset("utf8mb4");

            return $conexion;

        }catch(mysqli_sql_exception){
           throw new Exception("No se pudo conectar con la base de datos", 500);
        }
        }
    }
?>