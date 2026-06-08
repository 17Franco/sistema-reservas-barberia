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
use Barberia\Backend\dominio\repositorio\RepositorioDisponibilidad;
use Barberia\Backend\dominio\ServicioBarberia;
use DateTime;
use LDAP\Result;
use mysqli;


    class RepositorioDisponibilidadImpl implements RepositorioDisponibilidad{
        
        private mysqli $conn;

        public function __construct(mysqli $conn) {
            $this->conn = $conn;
        }

        public function listarServicios(): array{
            $sql ="SELECT * FROM servicios s ";
            
            $result = $this->conn->query($sql);
            $servicios =[];
            while ($row = $result->fetch_assoc()) {
                $servicio = new ServicioBarberia($row['nombre'],$row['descripcion'],(int)$row['duracion'],(int)$row['precio']);
                $servicio->setIdServicio((int)$row['idServicio']);
                $servicios[]=$servicio;
            }
            
            return $servicios;
        }

        public function empleadosPorServicio(int $idServicio):array{
            $sql ="SELECT idEmpleado FROM empleado_servicios es where es.idServicio= ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i",$idServicio);

            $stmt->execute();

            $result = $stmt->get_result();
            $empleados =[];
            while ($rowEmpleado  = $result->fetch_assoc()) {
                $empleados[]=$rowEmpleado;
            }
            
            return $empleados;
        }

    }
?>