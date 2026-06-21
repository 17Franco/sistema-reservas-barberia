<?php
namespace Barberia\Backend\infraestructura\persistencia;

use Barberia\Backend\dominio\repositorio\RepositorioReserva;
use Barberia\Backend\dominio\Reserva;
use Barberia\Backend\dominio\EstadoReserva;
use Exception;
use mysqli;

class RepositorioReservaImpl implements RepositorioReserva{
    
    private mysqli $conn;

    public function __construct(mysqli $conn) {
        $this->conn = $conn;
    }

    public function save(Reserva $reserva):int{
        $sql = "INSERT INTO reservas(idCliente, idEmpleado, idServicio, fecha, horaInicio, horaFin, estado) VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);

        $idCliente = $reserva->getIdCliente();
        $idEmpleado = $reserva->getIdEmpleado();
        $idServicio = $reserva->getIdServicio();
        $fecha = $reserva->getFecha();
        $horaInicio = $reserva->getHoraIni();
        $horaFin = $reserva->getHoraFin();
        $estado = "PENDIENTE";
        
        $stmt->bind_param(
            "iiissss",
            $idCliente,
            $idEmpleado,
            $idServicio,
            $fecha,
            $horaInicio,
            $horaFin,
            $estado
        );

        $stmt->execute();

        // Verifico que realmente insertó una fila
        if ($stmt->affected_rows !== 1) {
            throw new Exception("No se pudo guardar la reserva", 500);
        }

        // Obtengo el id 
        $idReserva = $stmt->insert_id;

        return $idReserva;
    }
    
    public function existeReserva(int $IdEmpleado, string $fecha, string $horaIni, string $horaFin):bool{
        $sql = "SELECT 1 FROM reservas WHERE idEmpleado = ? AND fecha = ? AND estado = 'PENDIENTE' AND horaInicio < ? AND horaFin > ? LIMIT 1";

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param("isss", $IdEmpleado,$fecha,$horaFin,$horaIni);

        $stmt->execute();

        $result = $stmt->get_result();

        return $result->num_rows > 0;

    }
    public function estaEnHorarioLaboralEmpleado(int $idEmpleado,string $horaInicio,string $horaFin):bool{
        // $sql = "SELECT 1 FROM horario_empleado WHERE idEmpleado = ? AND ? >= horaIni AND ? <= horaFin LIMIT 1";
        $sql = "SELECT 1 FROM horario_empleado WHERE idEmpleado = ? AND ? >= horaIni AND ? <= horaFin AND (horaDescansoIni = '00:00:00' OR NOT (? < horaDescansoFin AND ? > horaDescansoIni)) LIMIT 1";

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param("issss", $idEmpleado,$horaInicio,$horaFin,$horaInicio,$horaFin);

        $stmt->execute();

        $result = $stmt->get_result();

        return $result->num_rows > 0;
    }

    public function obtenerReserva(int $idReserva):?Reserva{
        $reserva= null;

        $sql = "SELECT * FROM reservas where idReserva = ?";
        
        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param("i", $idReserva);

        $stmt->execute();

        $result = $stmt->get_result();

        $rowReserva = $result->fetch_assoc();

        if (!$rowReserva) {
            return null;
        }
        $reserva = new Reserva(
            $rowReserva['idServicio'],
            $rowReserva['idEmpleado'],
            $rowReserva['fecha'],
            $rowReserva['horaInicio']);

        $reserva->setIdReserva($rowReserva['idReserva']);
        $reserva->setHoraFin($rowReserva['horaFin']);
        $reserva->setIdCliente($rowReserva['idCliente']);
        
        $reserva->setEstadoReserva(
            EstadoReserva::from($rowReserva['estado'])
        );
        return $reserva;
    }




    public function cancelar(int $idReserva, int $idCliente): bool{
        $sql = "UPDATE reservas
                SET estado = 'CANCELADA'
                WHERE idReserva = ?
                AND idCliente = ?
                AND estado = 'PENDIENTE'";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $idReserva, $idCliente);
        $stmt->execute();

        return $stmt->affected_rows === 1;
    }



    public function confirmar(int $idReserva, int $idCliente): bool{
        $sql = "UPDATE reservas
                SET estado = 'CONFIRMADA'
                WHERE idReserva = ?
                AND idCliente = ?
                AND estado = 'PENDIENTE'";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $idReserva, $idCliente);
        $stmt->execute();

        return $stmt->affected_rows === 1;
    }

}
?>