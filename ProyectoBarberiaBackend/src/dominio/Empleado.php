<?php
namespace Barberia\Backend\dominio;

use Barberia\Backend\dominio\Usuario;
use DateTime;

class Empleado extends Usuario {
    
    /**
     * el array tendra el horario del empleado
     */
    private array $horarios = [];
    private EstadoEmpleado $estado;
    private string $especialidad;
    
    private bool $disponible=false;

    public function __construct(string $ci,string $nombre,string $apellido,DateTime $fechaNac,string $pass,string $email,string $cel,EstadoEmpleado $estado) {

        parent::__construct($ci,$nombre,$apellido,$fechaNac,$pass,$email,$cel);
        $this->estado = $estado;
    }


    public function getEstado(): EstadoEmpleado {
        return $this->estado;
    }
    public function getDisponible(): bool {
        return $this->disponible;
    }

    public function setEstado(EstadoEmpleado $estado): void {
        $this->estado = $estado;
    }
    public function setDisponible(bool $disponible): void {
        $this->disponible = $disponible;
    }
    public function getEspecialidad(): string {
        return $this->especialidad;
    }


    public function setEspecialidad(string $especialidad): void {
        $this->especialidad = $especialidad;
    }

    public function agregarHorario(Horario_Empleado $horario): void
    {
        $this->horarios[] = $horario;
    }

    //retorna la lista horario
    public function getHorarios(): array
    {
        return $this->horarios;
    }
}
?>