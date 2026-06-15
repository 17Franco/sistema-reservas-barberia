<?php
    // se calcula desde pos del archivo actual
    //../ baja carpeta una ves 
    namespace Barberia\Backend\aplicacion;

use Barberia\Backend\dominio\Dia;

    interface ServiciosDisponibilidad {

        //diasDiponibles devuelve una lista de fecha en las que todavia entran reservas
        public function calendario(): array;

        public function disponibilidadServicios(string $dia): array;

        public function barberoServicioDisponiblePorDia(string $dia, int $idServicio):array;

        public function horariosDiponiblesDia(string $dia, int $idServicio,int $idEmpleado):array;
        
       
    }
?>