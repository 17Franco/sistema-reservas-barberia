<?php
namespace Barberia\Backend\dominio;

use DateTime;

class Reserva {

    private int $idServicio;
    private string $idEmpleado;
    private string $idUsuario;
    private DateTime $fecha;
    private string $hora;

    public function __construct(int $idServicio,string $idEmpleado,string $idUsuario,DateTime $fecha,string $hora) {
        $this->idServicio = $idServicio;
        $this->idEmpleado = $idEmpleado;
        $this->idUsuario = $idUsuario;
        $this->fecha = $fecha;
        $this->hora = $hora;
    }

    public function getIdServicio(): int {
        return $this->idServicio;
    }

    public function setIdServicio(int $idServicio): void {
        $this->idServicio = $idServicio;
    }

    public function getIdEmpleado(): string {
        return $this->idEmpleado;
    }

    public function setIdEmpleado(string $idEmpleado): void {
        $this->idEmpleado = $idEmpleado;
    }

    public function getIdUsuario(): string {
        return $this->idUsuario;
    }

    public function setIdUsuario(string $idUsuario): void {
        $this->idUsuario = $idUsuario;
    }

    public function getFecha(): DateTime {
        return $this->fecha;
    }

    public function setFecha(DateTime $fecha): void {
        $this->fecha = $fecha;
    }

    public function getHora(): string {
        return $this->hora;
    }

    public function setHora(string $hora): void {
        $this->hora = $hora;
    }

}
?>