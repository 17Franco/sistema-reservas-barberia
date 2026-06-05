<?php

namespace Barberia\Backend\infraestructura\persistencia;

use Barberia\Backend\dominio\repositorio\RepositorioEmpleado;
use Barberia\Backend\dominio\Empleado;
use Barberia\Backend\dominio\TipoUsuario;
use Barberia\Backend\dominio\EstadoEmpleado;
use DateTime;
use mysqli;

class RepoEmpleadoImpl implements RepositorioEmpleado {

    private mysqli $conn;

    public function __construct(mysqli $conn) {
        $this->conn = $conn;
    }

    public function listar(): array {

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

    public function actualizar(Empleado $e): bool {

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

    public function buscarPorCi(string $ci): ?Empleado {
        return null;
    }
}