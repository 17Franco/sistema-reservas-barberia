<?php
    namespace Barberia\Backend\aplicacion\impl;

    use Barberia\Backend\aplicacion\ServiciosDisponibilidad;
    use Barberia\Backend\dominio\Dia;
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

        public function disponibilidadServicioDia(Dia $dia, ServicioBarberia $servicio){
            //aca me traigo los empleados para ese servicio sus horarios y reservas e intento generar slot disponible del servicio al empleado 
            //si puedo detengo y devuelvo el dia disponible 
            //sino sigo hasta ver todo los empleados
        }
        
        public function disponibilidadDia(Dia $dia){

            //debo traeme los servicios
            
            //aca me traigo los servicios y 
            //luego llamo a disponibilidadServicioDia

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



            return $dias;
        }

    }
?>