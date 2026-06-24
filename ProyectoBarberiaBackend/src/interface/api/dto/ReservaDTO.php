<?php

namespace Barberia\Backend\interface\api\dto;


use Barberia\Backend\interface\api\dto\ClienteDTO;
use Barberia\Backend\interface\api\dto\EmpleadoDTO;
use Barberia\Backend\interface\api\dto\ServicioDTO;

class ReservaDTO{

    public function __construct(
        public int $idReserva,
        public ClienteDTO $cliente,
        public EmpleadoDTO $empleado,
        public ServicioDTO $servicio,
        public string $fecha,
        public string $horaInicio,
        public string $horaFin,
        public int $duracion,
        public string $estado
    ) {}
}
?>