<?php
namespace Barberia\Backend\interface\api\dto;
class ReservasPorFechaDTO
{
    /**
     * @param ReservaDTO[] $reservas
     */
    public function __construct(
        public string $fecha,
        public int $cantidadReservas,
        public array $reservas
    ) {}
}

?>