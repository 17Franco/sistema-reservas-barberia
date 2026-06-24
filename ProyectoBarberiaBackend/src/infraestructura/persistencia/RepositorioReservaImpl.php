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




    public function cancelar(int $idReserva, int $idUsuario, bool $esEmpleado): bool{
        $columnaUsuario = $esEmpleado ? 'idEmpleado' : 'idCliente';
        $sql = "UPDATE reservas
                SET estado = 'CANCELADA'
                WHERE idReserva = ?
                AND {$columnaUsuario} = ?
                AND estado = 'PENDIENTE'";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $idReserva, $idUsuario);
        $stmt->execute();

        return $stmt->affected_rows === 1;
    }



    public function confirmar(int $idReserva, int $idUsuario, bool $esEmpleado): bool{
        $columnaUsuario = $esEmpleado ? 'idEmpleado' : 'idCliente';
        $sql = "UPDATE reservas
                SET estado = 'CONFIRMADA'
                WHERE idReserva = ?
                AND {$columnaUsuario} = ?
                AND estado = 'PENDIENTE'";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $idReserva, $idUsuario);
        $stmt->execute();

        return $stmt->affected_rows === 1;
    }

    public function completar(int $idReserva, int $idUsuario, bool $esAdmin): bool{
        $filtroUsuario = $esAdmin ? '' : 'AND idEmpleado = ?';
        $sql = "UPDATE reservas
                SET estado = 'COMPLETADA'
                WHERE idReserva = ?
                {$filtroUsuario}
                AND estado = 'CONFIRMADA'";

        $stmt = $this->conn->prepare($sql);
        if ($esAdmin) {
            $stmt->bind_param("i", $idReserva);
        } else {
            $stmt->bind_param("ii", $idReserva, $idUsuario);
        }
        $stmt->execute();

        return $stmt->affected_rows === 1;
    }

    /*
     $filters = [
        'servicio' => $servicio,
        'estado' => $estado,
        'empleado' => $empleado,
        'fechaDesde' => $fechaDesde,
        'fechaHasta' => $fechaHasta,
    ];
    */
    public function buscarConFiltros(array $filtros):array{
        $reservas = [];
        $sql = "SELECT r.idReserva,r.fecha,r.horaInicio,r.horaFin,r.estado,u.id AS cliente_id,u.nombre AS cliente_nombre,u.celular AS cliente_celular,
        u.email AS cliente_email,u.foto AS cliente_foto,e.id AS empleado_id,e.nombre AS empleado_nombre,s.idServicio AS servicio_id,
        s.nombre AS servicio_nombre,s.duracion AS servicio_duracion FROM reservas r INNER JOIN usuarios u ON r.idCliente = u.id
        INNER JOIN usuarios e ON r.idEmpleado = e.id INNER JOIN servicios s ON r.idServicio = s.idServicio WHERE r.fecha >= ?" ;
        
        $params = [];
        $tipos = "";
        $params[] = $filtros['fechaDesde'];
        $tipos .= "s";

        //agrego filtro si viene y no es null
        if ($filtros['fechaHasta']) {
            $sql .= " AND r.fecha <= ?";
            $params[] = $filtros['fechaHasta'];
            $tipos .= "s";
        }

         if ($filtros['servicio']) {
            $sql .= " AND r.idServicio = ?";
            $params[] = $filtros['servicio'];
            $tipos .= "i";
        }

        if ($filtros['empleado']) {
            $sql .= " AND r.idEmpleado = ?";
            $params[] = $filtros['empleado'];
            $tipos .= "i";
        }

        if ($filtros['estado']) {
            $sql .= " AND r.estado = ?";
            $params[] = $filtros['estado'];
            $tipos .= "s";
        }

        //agrego despues de agregar los filtros 
        $sql .= " ORDER BY r.fecha ASC, r.horaInicio ASC";
        
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception($this->conn->error);
        }

        $stmt->bind_param($tipos, ...$params);

        $stmt->execute();
        $resultado = $stmt->get_result();

        while ($fila = $resultado->fetch_assoc()) {
            $reservas[] = $fila;
        }
        $stmt->close();
        return $reservas;

    }
}
?>
