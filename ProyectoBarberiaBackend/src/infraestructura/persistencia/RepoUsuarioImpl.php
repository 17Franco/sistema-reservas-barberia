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
use DateTime;
use LDAP\Result;
use mysqli;


    class RepoUsuarioImpl implements RepositorioUsuario{
        
        private mysqli $conn;

        public function __construct(mysqli $conn) {
            $this->conn = $conn;
        }

        
        public function guardarCliente(Cliente $u): bool{

                $sql = "INSERT INTO usuarios(ci,nombre,apellido,fechaNac,password_hash,email,foto,celular,tipoUsuario) VALUES (?,?,?,?,?,?,?,?,?)"; 
                
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

                $stmt->bind_param("sssssssss", $ci, $nombre,$apellido,$fechaNac, $pass, $email, $foto, $cel,$tipo);

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
        public function existe(string $ci): bool{
            $sql = "SELECT * FROM usuarios WHERE ci = ?";

            $stmt = $this->conn->prepare($sql);

            $stmt->bind_param("s", $ci);

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
                    $result->setTipo(TipoUsuario::from($data['tipoUsuario']));
                    $result->setId($data['id']);
                    $result->setFoto($data['foto']);
                }
            }
            
            return $result; 
        }

            public function listarEmpleado(): array {

            $sql = "SELECT u.*, e.horaInicio, e.horaFin, e.estado, e.especialidad
                    FROM usuarios u
                    INNER JOIN empleado e ON u.ci = e.ci";

            $result = $this->conn->query($sql);

            $lista = [];

            while ($row = $result->fetch_assoc()) {

                $lista[] = new Empleado(
                    $row['ci'],
                    $row['nombre'],
                    $row['apellido'],
                    new DateTime($row['fechaNac']),
                    $row['contraseña'],
                    $row['email'],
                    $row['celular'],
                    TipoUsuario::from($row['tipoUsuario']),
                    $row['horaInicio'],
                    $row['horaFin'],
                    EstadoEmpleado::from($row['estado'])
                );
            }

            return $lista;
        }

        public function actualizarEmpleado(Empleado $e): bool {

            $sql = "UPDATE empleado 
                    SET horaInicio=?, horaFin=?, estado=?, especialidad=? 
                    WHERE ci=?";

            $stmt = $this->conn->prepare($sql);

            $hi = $e->getHoraIni();
            $hf = $e->getHoraFin();
            $estado = $e->getEstado()->value;
            $esp = 1;
            $ci = $e->getCi();

            $stmt->bind_param("sssis", $hi, $hf, $estado, $esp, $ci);

            return $stmt->execute();
        }

        public function buscarPorCiEmpleado(string $ci): ?Empleado {
            return null;
        }

        public function actualizar(Usuario $usuario): bool{return false;}

        public function eliminar(string $id): bool{return false;}

        public function buscarPorId(string $id): ?Cliente{return null;}

        public function listar(): array{return [];}

    }
?>