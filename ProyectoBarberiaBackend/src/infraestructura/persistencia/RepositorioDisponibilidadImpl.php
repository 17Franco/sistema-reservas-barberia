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
use Barberia\Backend\dominio\repositorio\RepositorioDisponibilidad;
use Barberia\Backend\dominio\Reserva;
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

        public function obtenerIdsEmpleadosPorServicio(int $idServicio):array{
            $sql ="SELECT idEmpleado FROM empleado_servicios es where es.idServicio= ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i",$idServicio);

            $stmt->execute();

            $result = $stmt->get_result();
            $empleados =[];
            while ($rowEmpleado  = $result->fetch_assoc()) {
                $empleados[]=(int)$rowEmpleado['idEmpleado'];
            }
            
            return $empleados;
        }

       public function horarioEmpleado(int $empleado):array{
            $sql="SELECT * FROM horario_empleado he WHERE he.idEmpleado= ? ORDER BY horaIni";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i",$empleado);

            $stmt->execute();

            $result = $stmt->get_result();
            $horario=[];

            while ($rowHorario = $result->fetch_assoc()) {
                    $horarioEmp = new Horario_Empleado($rowHorario['horaIni'],$rowHorario['horaFin']);
                    $horarioEmp->setHoraIniDescanso($rowHorario['horaDescanzoIni']);
                    $horarioEmp->setHoraFinDescanso($rowHorario['horaDescanzoFin']);
                    $horario[]=$horarioEmp;
            }
            return $horario;
       }

       public function reservasPorFechaAEmpleado(string $fecha,int $empleado):array{
            $sql = "SELECT * FROM reservas r where r.idEmpleado= ? and r.fecha= ? ORDER BY horainicio";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("is",$empleado,$fecha);

            $stmt->execute();

            $result = $stmt->get_result();
            $reservas=[];
            while ($rowReserva = $result->fetch_assoc()) {
                $reserva = new Reserva(
                    (int)$rowReserva["idServicio"],
                    (int)$rowReserva["idEmpleado"],
                    (int)$rowReserva["idCliente"],
                    $rowReserva["fecha"],
                    $rowReserva["horaInicio"],
                    $rowReserva["horaFin"]
                );
                $reserva->setidReserva((int)$rowReserva["idReserva"]);
                $reservas[]=$reserva;
            }
            
            return $reservas;
       }

       public function EmpleadosPorServicio(int $idServicio):array{
        $sql = "SELECT u.*,e.estado,e.especialidad,s.nombre as nomEspecialidad FROM empleado_servicios es 
                INNER JOIN empleado e ON e.id_usuario=es.idEmpleado 
                INNER JOIN usuarios u on e.id_usuario=u.id 
                INNER JOIN servicios s on e.especialidad=s.idServicio where es.idServicio= ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i",$idServicio);  
        $stmt->execute();

        $result = $stmt->get_result();     
        $empleados=[];
        while ($rowEmpleado = $result->fetch_assoc()) {
                $empleado = new Empleado(
                $rowEmpleado['ci'],
                $rowEmpleado['nombre'],
                $rowEmpleado['apellido'],
                new DateTime($rowEmpleado['fechaNac']),
                $rowEmpleado['password_hash'],//no mandar
                $rowEmpleado['email'],
                $rowEmpleado['celular'],
                EstadoEmpleado::from($rowEmpleado['estado'])
            );
            $empleado->setId($rowEmpleado['id']);
            $empleado->setFoto($rowEmpleado['foto']);
            $empleado->setEspecialidad($rowEmpleado['nomEspecialidad']);
                
            $empleados[]=$empleado;
            }

        return $empleados;
       }

       public function obtenerServicioPorId(int $idServicio):?ServicioBarberia{
            $sql="SELECT * FROM servicios s WHERE s.idServicio= ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i",$idServicio);  
            $stmt->execute();
            
            $result = $stmt->get_result();
            $rowServicio = $result->fetch_assoc();

            if (!$rowServicio) {
                return null;
            }

            return new ServicioBarberia(
                $rowServicio['idServicio'],
                $rowServicio['nombre'],
                $rowServicio['duracion'],
                $rowServicio['precio']
            );
       
       }
    }
?>