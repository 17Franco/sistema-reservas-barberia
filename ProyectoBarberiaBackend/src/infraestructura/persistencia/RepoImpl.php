<?php
namespace Barberia\Backend\infraestructura\persistencia;

use ArrayObject;
use Barberia\Backend\dominio\repositorio\Repositorio;
use Barberia\Backend\dominio\repositorio\RepositorioUsuario;
use Barberia\Backend\dominio\TipoUsuario;
use Barberia\Backend\dominio\Usuario;
use Barberia\Backend\dominio\Cliente;
use DateTime;
use LDAP\Result;
use mysqli;


    class RepoImpl implements RepositorioUsuario{
        
        private mysqli $conn;

        public function __construct(mysqli $conn) {
            $this->conn = $conn;
        }

        
        public function guardarCliente(Cliente $u): bool{

                $sql = "INSERT INTO usuarios(ci,nombre,apellido,fechaNac,contraseña,email,foto,celular,tipoUsuario) VALUES (?,?,?,?,?,?,?,?,?)";
                $sql2 = "INSERT INTO cliente(ci) VALUES (?)";

                $stmt = $this->conn->prepare($sql);
                $stmt2 = $this->conn->prepare($sql2);

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
                $stmt2->bind_param("s", $ci);


                return $stmt->execute() && $stmt2->execute();
            
           // return true;
        }

        public function existe(string $ci): bool{
            $sql = "SELECT * FROM usuarios WHERE ci = ?";

            $stmt = $this->conn->prepare($sql);

            $stmt->bind_param("s", $ci);

            $stmt->execute();

            $result = $stmt->get_result();

            return $result->num_rows > 0;

        }
       
        public function verificar(string $ci, string $pass): ?Usuario{
            $result = null;
            if($this->existe($ci)){
                //creo la consulta
                $sql = "SELECT * FROM usuarios WHERE ci = ? and contraseña = ? ";
                
                //la preparo
                $stmt = $this->conn->prepare($sql);
                
                //inserta las variables/datos en la cosnulta 
                $stmt->bind_param("ss",$ci,$pass);

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
                    $result = new Usuario($data['ci'],$data['nombre'],$data['apellido'],$fecha,$data['contraseña'],$data['email'],$data['celular']);
                    $result->setTipo(TipoUsuario::from($data['tipoUsuario']));
                }
            }
            
            return $result; 
        }

        public function actualizar(Usuario $usuario): bool{return false;}

        public function eliminar(string $id): bool{return false;}

        public function buscarPorId(string $id): ?Usuario{return null;}

        public function listar(): array{return [];}

    }
?>