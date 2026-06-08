<?php
    namespace Barberia\Backend\aplicacion\impl;

    use Barberia\Backend\aplicacion\ServiciosDisponibilidad;
    use Barberia\Backend\dominio\Dia;
use Barberia\Backend\dominio\Empleado;
use Barberia\Backend\dominio\repositorio\RepositorioDisponibilidad;
    use Barberia\Backend\dominio\ServicioBarberia;
    use DateTime;

    class ServiciosDisponibilidadImpl implements ServiciosDisponibilidad {

        //variable donde guardo la interfaz repo
        private RepositorioDisponibilidad $repo;

        //Inyección por constructo
        public function __construct(RepositorioDisponibilidad $repo) {
            
            $this->repo = $repo;
        }

        public function diaInglesToEspanol(string $dia){
            $diasSemana = [
            "Monday" => "Lunes",
            "Tuesday" => "Martes",
            "Wednesday" => "Miércoles",
            "Thursday" => "Jueves",
            "Friday" => "Viernes",
            "Saturday" => "Sábado",
            "Sunday" => "Domingo"
            ];
            return $diasSemana[$dia];
        }
        public function mesInglesToEspanol(string $mes){
            $meses = [
                "January" => "Enero",
                "February" => "Febrero",
                "March" => "Marzo",
                "April" => "Abril",
                "May" => "Mayo",
                "June" => "Junio",
                "July" => "Julio",
                "August" => "Agosto",
                "September" => "Septiembre",
                "October" => "Octubre",
                "November" => "Noviembre",
                "December" => "Diciembre"
            ];
            return $meses[$mes];
        }

        public function disponibilidadEmpleadoDia(Dia $dia, ServicioBarberia $servicio, Empleado $empleado ):bool{
            //aca me traigo el horario del empleado y las reservas que son de el para ese dia 
            //si no tiene reserva para ese dia devuelvo true 
            //si tiene debo ver si puedo generar un espacion de la duracion del servicio es su horario sin chocar con otra reserva ni su media hora libre 

            return true;
        }

        public function disponibilidadServicioDia(Dia $dia, ServicioBarberia $servicio): bool{
            //aca traigo lista de empleado que realizan ese servicio
            //recorro y llamo a disponibilidadEmpleadoDia
            return true;
            
        }
        
        public function tieneDisponibilidad(Dia $dia): bool{
            //debo traeme los servicios
            $servicios = $this->repo->listarServicios();
            //recorro
            foreach ($servicios as $servicio) {
             $empleados = $this->repo->empleadosPorServicio($servicio);
                foreach ($empleados as $empleado) {
                    if ($this->disponibilidadEmpleadoDia($dia,$servicio,$empleado)) {
                        return true;
                    }
                }  
            }
            return false;
        }

        public function disponibilidadDias(): array{
            //genero array de 30 dias 
            $dias = [];
            $hoy = new DateTime();
            $horaActual = $hoy->format("H");
            if($horaActual > 21){
                 $hoy->modify("+1 day");
            }

            for ($i = 0; $i < 30; $i++) {

                $fecha = clone $hoy;
                $fecha->modify("+$i days");

                $dia = new Dia();

                $dia->setFecha($fecha->format("Y-m-d"));
                $dia->setDia($this->diaInglesToEspanol($fecha->format("l")));
                $dia->setNumeroDia((int)$fecha->format("d"));
                $dia->setMes($this->mesInglesToEspanol($fecha->format("F")));
                $dia->setDisponible(false);

                $dias[] = $dia;
            }
            //ahora debo ver si caben mas turnos o no en esos dias osea si ya estan full
            foreach ($dias as $dia) {
                if($dia->getDia()!="Domingo"){//domino no abre 
                    $dia->setDisponible($this->tieneDisponibilidad($dia));//para cada dia mando a fijarse diponibilidad 
                }
             
            }


            return $dias;
        }

    }
?>