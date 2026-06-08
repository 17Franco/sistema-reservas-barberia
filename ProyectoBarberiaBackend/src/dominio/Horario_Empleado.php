<?php
namespace Barberia\Backend\dominio;

use Barberia\Backend\dominio\Usuario;
use DateTime;

    class Horario_Empleado{
    private string $horaIni;
    private string $horaFin;
    private ?string $horaIniDescanso = null;
    private ?string $horaFinDescanso = null;

    public function __construct(string $horaIni, string $horaFin)
    {
        $this->horaIni = $horaIni;
        $this->horaFin = $horaFin;
    }

    public function getHoraIni(): string
    {
        return $this->horaIni;
    }

    public function setHoraIni(string $horaIni): void
    {
        $this->horaIni = $horaIni;
    }

    public function getHoraFin(): string
    {
        return $this->horaFin;
    }

    public function setHoraFin(string $horaFin): void
    {
        $this->horaFin = $horaFin;
    }

    public function getHoraIniDescanso(): ?string
    {
        return $this->horaIniDescanso;
    }

    public function setHoraIniDescanso(?string $horaIniDescanso): void
    {
        $this->horaIniDescanso = $horaIniDescanso;
    }

    public function getHoraFinDescanso(): ?string
    {
        return $this->horaFinDescanso;
    }

    public function setHoraFinDescanso(?string $horaFinDescanso): void
    {
        $this->horaFinDescanso = $horaFinDescanso;
    }
}
?>