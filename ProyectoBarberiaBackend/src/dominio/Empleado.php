<?php
namespace Barberia\Backend\dominio;

use Barberia\Backend\dominio\Usuario;
use DateTime;

class Empleado extends Usuario {

    private string $horaIni;
    private string $horaFin;
    private EstadoEmpleado $estado;

    public function __construct(string $ci,string $nombre,string $apellido,DateTime $fechaNac,string $pass,string $email,string $cel,TipoUsuario $tipo,string $horaIni,string $horaFin,EstadoEmpleado $estado) {

        parent::__construct($ci,$nombre,$apellido,$fechaNac,$pass,$email,$cel,$tipo);
        $this->horaIni = $horaIni;
        $this->horaFin = $horaFin;
        $this->estado = $estado;
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

    public function getEstado(): EstadoEmpleado {
        return $this->estado;
    }

    public function setEstado(EstadoEmpleado $estado): void {
        $this->estado = $estado;
    }
}
?>