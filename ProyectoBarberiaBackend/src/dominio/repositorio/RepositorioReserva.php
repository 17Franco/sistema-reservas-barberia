<?php
namespace Barberia\Backend\dominio\repositorio;

use Barberia\Backend\dominio\Reserva;

    interface RepositorioReserva {
        public function save(Reserva $reserva):int;
        public function existeReserva(int $IdEmpleado, string $fecha, string $horaIni, string $horaFin):bool;
        public function estaEnHorarioLaboralEmpleado(int $idEmpleado,string $horaInicio,string $horaFin):bool;
        public function obtenerReserva(int $idReserva):?Reserva;
        public function cancelar(int $idReserva, int $idCliente): bool;
        public function confirmar(int $idReserva, int $idCliente): bool;
        }
?>