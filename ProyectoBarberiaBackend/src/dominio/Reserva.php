<?php
namespace Barberia\Backend\dominio;
use DateTime;
use Barberia\Backend\dominio\EstadoReserva;

class Reserva {

    private int $idReserva;
    private int $idServicio;
    private int $idEmpleado;
    private int $idCliente;
    private string $fecha;
    private string $horaIni;
    private string $horaFin;
    private EstadoReserva $estado;

    public function __construct(
        int $idServicio,
        int $idEmpleado,
        int $idCliente,
        string $fecha,
        string $horaIni,
        string $horaFin,
        EstadoReserva $estado
    ) {
        $this->idServicio = $idServicio;
        $this->idEmpleado = $idEmpleado;
        $this->idCliente = $idCliente;
        $this->fecha = $fecha;
        $this->horaIni = $horaIni;
        $this->horaFin = $horaFin;
        $this->estado = $estado;
    }
    //GETTERS
    public function getIdReserva(): int {
        return $this->idReserva;
    }

    public function setIdReserva(int $idReserva): void {
        $this->idReserva = $idReserva;
    }

    public function getIdServicio(): int {
        return $this->idServicio;
    }

    public function getEstadoReserva(): EstadoReserva {
        return $this->estado;
    }

    //SETTERS

    public function setIdServicio(int $idServicio): void {
        $this->idServicio = $idServicio;
    }

    public function getIdEmpleado(): int {
        return $this->idEmpleado;
    }

    public function setIdEmpleado(int $idEmpleado): void {
        $this->idEmpleado = $idEmpleado;
    }

    public function getIdCliente(): int {
        return $this->idCliente;
    }

    public function setIdCliente(int $idCliente): void {
        $this->idCliente = $idCliente;
    }

    public function getFecha(): string {
        return $this->fecha;
    }

    public function setFecha(string $fecha): void {
        $this->fecha = $fecha;
    }

    public function getHoraIni(): string {
        return $this->horaIni;
    }

    public function setHoraIni(string $horaIni): void {
        $this->horaIni = $horaIni;
    }

    public function getHoraFin(): string {
        return $this->horaFin;
    }

    public function setHoraFin(string $horaFin): void {
        $this->horaFin = $horaFin;
    }

    public function setEstadoReserva(Estadoreserva $estado): void {
        $this->estado = $estao;
    }
}
?>