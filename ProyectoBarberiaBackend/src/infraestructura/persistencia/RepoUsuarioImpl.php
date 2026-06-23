<?php
namespace Barberia\Backend\infraestructura\persistencia;

use ArrayObject;
use Barberia\Backend\dominio\repositorio\Repositorio;
use Barberia\Backend\dominio\repositorio\RepositorioUsuario;
use Barberia\Backend\dominio\TipoUsuario;
use Barberia\Backend\dominio\Usuario;
use Barberia\Backend\dominio\Cliente;
use Barberia\Backend\dominio\Empleado;
use Barberia\Backend\dominio\EstadoEmpleado;
use Barberia\Backend\dominio\Horario_Empleado;
use DateTime;
use Exception;
use LDAP\Result;
use mysqli;


    class RepoUsuarioImpl implements RepositorioUsuario{
        
        private mysqli $conn;

        public function __construct(mysqli $conn) {
            $this->conn = $conn;
        }

        
        public function guardarCliente(Cliente $u): bool{

                $sql = "INSERT INTO usuarios(ci,nombre,apellido,fechaNac,password_hash,email,foto,celular,tipoUsuario,fechaCreacion) VALUES (?,?,?,?,?,?,?,?,?,?)"; 
                
                $stmt = $this->conn->prepare($sql);
                

                $ci = $u->getCi();
                $nombre = $u->getNombre();
                $apellido = $u->getApellido();
                $fechaNac = $u->getFechaNac()->format('Y-m-d');
                $pass = $u->getPass();
                $email = $u->getEmail();
                $foto = $u->getFoto();
                $cel = $u->getCel();
                $tipo = "CLIENTE";
                $fechaHoy = date('Y-m-d');//FECHA CREACION LA SACO DEL DIA ACTUAL
                $stmt->bind_param("ssssssssss", $ci, $nombre,$apellido,$fechaNac, $pass, $email, $foto, $cel,$tipo,$fechaHoy);

                if($stmt->execute()){
                    $id = $this->conn->insert_id; //obtiene ultimo id de la ultima consulta echa
                    $sql2 = "INSERT INTO cliente (id_Usuario) VALUES (?)";
                    $stmt2 = $this->conn->prepare($sql2);
                    $stmt2->bind_param("i", $id);

                    return $stmt2->execute();
                }
                

                return false;
            
           // return true;
        }

        //comprueba ci
        public function existeClientePorCi(string $ci): bool{
            $sql = "SELECT * FROM usuarios WHERE ci = ?";

            $stmt = $this->conn->prepare($sql);

            $stmt->bind_param("s", $ci);

            $stmt->execute();

            $result = $stmt->get_result();

            return $result->num_rows > 0;
        }
        
        public function existeClientePorId(int $id): bool{
            $sql = "SELECT * FROM cliente WHERE id_Usuario = ?";

            $stmt = $this->conn->prepare($sql);

            $stmt->bind_param("i", $id);

            $stmt->execute();

            $result = $stmt->get_result();

            return $result->num_rows > 0;
        }

        public function existeEmpleado(int $id): bool{
            $sql = "SELECT * FROM empleado WHERE id_usuario = ?";

            $stmt = $this->conn->prepare($sql);

            $stmt->bind_param("i", $id);

            $stmt->execute();

            $result = $stmt->get_result();

            return $result->num_rows > 0;

        }
        //comprueba correo
       public function emailUsado(string $email): bool{
            $sql = "SELECT * FROM usuarios WHERE email = ?";

            $stmt = $this->conn->prepare($sql);

            $stmt->bind_param("s", $email);

            $stmt->execute();

            $result = $stmt->get_result();

            return $result->num_rows > 0;

        }
        public function verificar(string $email, string $pass): ?Cliente{
            $result = null;
            if($this->emailUsado($email)){
                //creo la consulta
                $sql = "SELECT * FROM usuarios WHERE email = ? and password_hash = ? "; //esos datos estan en tabla usuarios
                
                //la preparo
                $stmt = $this->conn->prepare($sql);
                
                //inserta las variables/datos en la cosnulta 
                $stmt->bind_param("ss",$email,$pass);

                //ejecutamos
                $stmt->execute();

                $resuConsulta = $stmt->get_result();

                //controlo que la consulta con la pass devuelva algo
                if($resuConsulta->num_rows > 0){
                    //fetch_assoc devuelve un array asociativo donde las keys son los nombres de las columnas 
                    //solo una fila cada ves que se hace fetch_assoc
                    $data = $resuConsulta->fetch_assoc();

                    //como fecha debe ser DateTime la creo pasandole como parametro 
                    $fecha = new DateTime($data['fechaNac']);

                    //Creo usuario con todos sus datos 
                    $result = new Cliente($data['ci'],$data['nombre'],$data['apellido'],$fecha,$data['password_hash'],$data['email'],$data['celular']);
                    $result->setFechaCreacion($data['fechaCreacion']);
                    $result->setTipo(TipoUsuario::from($data['tipoUsuario']));
                    $result->setId($data['id']);
                    $result->setFoto($data['foto']);
                    $result->setDireccion($data['direccion']);
                }
            }
            
            return $result; 
        }

        public function listarEmpleado(): array {
            $sql = "SELECT u.id, u.ci, u.nombre, u.apellido, u.fechaNac, u.email, u.foto,
                           u.celular, e.estado, e.especialidad AS idEspecialidad,
                           s.nombre AS especialidad
                    FROM usuarios u
                    INNER JOIN empleado e ON e.id_usuario = u.id
                    INNER JOIN servicios s ON s.idServicio = e.especialidad
                    ORDER BY u.nombre";
            return $this->conn->query($sql)->fetch_all(MYSQLI_ASSOC);
        }
        public function getEmailById(int $idUsuario):?string{
            $sql = "SELECT email FROM usuarios WHERE id = ?";

            $stmt = $this->conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Error en la consulta");
            }

            $stmt->bind_param("i", $idUsuario);
            $stmt->execute();

            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            return $row['email'] ?? null;
        }
        
        public function guardarEmpleado(array $d): bool {
            $this->conn->begin_transaction();
            try {
                $stmt = $this->conn->prepare("INSERT INTO usuarios
                    (ci,nombre,apellido,fechaNac,password_hash,email,foto,celular,tipoUsuario,fechaCreacion)
                    VALUES (?,?,?,?,?,?,'/uploads/PerfilPorDefecto.png',?,'EMPLEADO',CURDATE())");
                $stmt->bind_param("sssssss", $d['ci'], $d['nombre'], $d['apellido'], $d['fechaNac'], $d['password'], $d['email'], $d['celular']);
                $stmt->execute();
                $id = $this->conn->insert_id;

                $stmt = $this->conn->prepare("INSERT INTO empleado (id_usuario,estado,especialidad) VALUES (?,'ACTIVO',?)");
                $stmt->bind_param("ii", $id, $d['idEspecialidad']);
                $stmt->execute();

                $descansoIni = $d['horaDescansoIni'] ?? '00:00';
                $descansoFin = $d['horaDescansoFin'] ?? '00:00';

                $stmt = $this->conn->prepare("
                    INSERT INTO horario_empleado
                    (idEmpleado, horaIni, horaFin, horaDescansoIni, horaDescansoFin)
                    VALUES (?, ?, ?, ?, ?)
                ");
                $stmt->bind_param("issss", $id, $d['horaIni'], $d['horaFin'], $descansoIni, $descansoFin);
                $stmt->execute();

                $stmt = $this->conn->prepare("INSERT INTO empleado_servicios (idEmpleado,idServicio) VALUES (?,?)");
                $stmt->bind_param("ii", $id, $d['idEspecialidad']);
                $stmt->execute();
                $this->conn->commit();
                return true;
            } catch (\Throwable $e) {
                $this->conn->rollback();
                throw $e;
            }
        }

        public function actualizarEmpleado(int $id, array $d): bool {
            $this->conn->begin_transaction();
            try {
                $stmt = $this->conn->prepare("UPDATE usuarios SET nombre=?,apellido=?,email=?,celular=? WHERE id=?");
                $stmt->bind_param("ssssi", $d['nombre'], $d['apellido'], $d['email'], $d['celular'], $id);
                $stmt->execute();

                $stmt = $this->conn->prepare("UPDATE empleado SET especialidad=? WHERE id_usuario=?");
                $stmt->bind_param("ii", $d['idEspecialidad'], $id);
                $stmt->execute();

                $stmt = $this->conn->prepare("DELETE FROM empleado_servicios WHERE idEmpleado=?");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $stmt = $this->conn->prepare("INSERT INTO empleado_servicios (idEmpleado,idServicio) VALUES (?,?)");
                $stmt->bind_param("ii", $id, $d['idEspecialidad']);
                $stmt->execute();
                $this->conn->commit();
                return true;
            } catch (\Throwable $e) {
                $this->conn->rollback();
                throw $e;
            }
        }

        public function cambiarEstadoEmpleado(string $ci, string $nuevoEstado): bool {
            // Apunta directamente a la tabla empleado filtrando por la CI del usuario
            $sql = "UPDATE empleado 
                    SET estado = ? 
                    WHERE id_usuario = (SELECT id FROM usuarios WHERE ci = ?)";

            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ss", $nuevoEstado, $ci);

            return $stmt->execute();
        }

        public function obtenerEmpleado(string $ci): ?Empleado {
            return null;
        }

        public function actualizar(Usuario $usuario): bool{return false;}

        public function eliminar(string $id): bool{return false;}

        public function obtenerCliente(string $id): ?Cliente{return null;}

        public function listar(): array{return [];}

        public function editarUsuario(int $idUsuario, string $nombre, string $apellido, string $celular, ?string $direccion, ?string $foto): bool{
            $sql = "UPDATE usuarios SET nombre = ?, apellido = ?, celular = ?, direccion = ?, foto = COALESCE(?, foto) WHERE id = ?";

            $consultaPreparada = $this->conn->prepare($sql);
            $consultaPreparada->bind_param("sssssi", $nombre, $apellido, $celular, $direccion, $foto, $idUsuario);

            return $consultaPreparada->execute();
        }

        public function obtenerCiPorId(int $idUsuario): ?string{
            $sql = "SELECT ci FROM usuarios WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $idUsuario);
            $stmt->execute();
            $fila = $stmt->get_result()->fetch_assoc();

            return $fila ? $fila['ci'] : null;
        }
    }
?>
