<?php
    namespace Barberia\Backend\dominio;

    class Dia
{
    private string $fecha;
    private string $dia;
    private int $numeroDia;
    private string $mes;
    private bool $disponible = false;

    // Constructor vacío
    public function __construct() {
    }

    // GETTERS

    public function getFecha(): string
    {
        return $this->fecha;
    }

    public function getDia(): string
    {
        return $this->dia;
    }

    public function getNumeroDia(): int
    {
        return $this->numeroDia;
    }

    public function getMes(): string
    {
        return $this->mes;
    }

    public function getDisponible(): bool
    {
        return $this->disponible;
    }

    // SETTERS

    public function setFecha(string $fecha): void
    {
        $this->fecha = $fecha;
    }

    public function setDia(string $dia): void
    {
        $this->dia = $dia;
    }

    public function setNumeroDia(int $numeroDia): void
    {
        $this->numeroDia = $numeroDia;
    }

    public function setMes(string $mes): void
    {
        $this->mes = $mes;
    }

    public function setDisponible(bool $disponible): void
    {
        $this->disponible = $disponible;
    }
}
?>