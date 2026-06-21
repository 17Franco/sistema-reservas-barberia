<?php
    namespace Barberia\Backend\aplicacion\impl;

    use Barberia\Backend\aplicacion\ServiciosDisponibilidad;
    use Barberia\Backend\dominio\Dia;
    use Barberia\Backend\dominio\Empleado;
    use Barberia\Backend\dominio\repositorio\RepositorioDisponibilidad;
    use Barberia\Backend\dominio\Reserva;
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
        //anda saber si funca esta poronga
        public function disponibilidadEmpleadoDia(string $dia, int $servicio, int $empleado): bool{
                //me traigo horario empleado
                $horarios = $this->repo->horarioEmpleado($empleado);
                //me traigo las reservas del epleado de tal dia
                $reservas = $this->repo->reservasPorFechaAEmpleado($dia, $empleado);

                foreach ($horarios as $horario) {
                    //comiezo horario laboral empleado
                    $inicio = new DateTime($horario->getHoraIni());
                    //fin del primer turno en caso de horario cortado
                    $fin    = new DateTime($horario->getHoraFin());
                    //fecha hoy
                    $hoy    = (new DateTime())->format('Y-m-d');

                    // Si la consulta es para el día de hoy entonce debo fijarme la hora actual
                    if ($dia === $hoy) {
                        $horaActual = new DateTime();
                        if ($horaActual > $inicio) {
                            $inicio = $horaActual;
                        }
                        // si tiene horario cortado y la hora actual ya supero el primer turno voy al otro turno
                        if ($inicio >= $fin) {
                            continue;//salto a sig horario osea siguiente turno
                        }
                    }

                    // guardo solo los bloqueso para el turno actual no me interesa otros
                    $bloqueosTurno = [];

                    //si horario no es cortado tiene descanzo me interesa tratarlo como un  bloqueo 
                    if (!empty($horario->getHoraIniDescanso()) && !empty($horario->getHoraFinDescanso()) &&
                        $horario->getHoraIniDescanso() !== "00:00:00" && $horario->getHoraFinDescanso() !== "00:00:00") {

                        $bloqueosTurno[] = ['inicio' => new DateTime($horario->getHoraIniDescanso()),'fin' => new DateTime($horario->getHoraFinDescanso())];
                    }
                    // Agrego las reservas que se superponen solo en el turno actual
                    foreach ($reservas as $reserva) {
                        $reservaInicio = new DateTime($reserva->getHoraIni());
                        $reservaFin    = new DateTime($reserva->getHoraFin());

                        // Una reserva pertenece o afecta a este turno si termina después del inicio del turno
                        // Y empieza antes de que el turno finalice
                        if ($reservaFin > $inicio && $reservaInicio < $fin) {
                            
                            // ajustamos
                            //si se cambia horario y ya exitia reserba anterior entonces esto lo arregla
                            //si el horario de la reserva es menor al inicio laboral entonces clono inicio sono queda con el de la reserva
                            $reservaInicioClamped = $reservaInicio < $inicio ? clone $inicio : $reservaInicio;
                            //lo mismo
                            $reservaFinClamped    = $reservaFin > $fin ? clone $fin : $reservaFin;

                            $bloqueosTurno[] = [
                                'inicio' => $reservaInicioClamped,
                                'fin'    => $reservaFinClamped
                            ];
                        }
                    }
                    // ordeno los bloqueo sentido horario
                    usort($bloqueosTurno, function ($a, $b) {
                        return $a['inicio'] <=> $b['inicio'];
                    });
                    //Buscar huecos libres entre los bloqueos del turno usando el cursor
                    $clonInicio = clone $inicio;

                    foreach ($bloqueosTurno as $bloqueo) {
                        // Calcular los minutos libres desde el cursor hasta el próximo bloqueo
                        $minutos = ($bloqueo['inicio']->getTimestamp() - $clonInicio->getTimestamp()) / 60;

                        if ($minutos >= $servicio) {
                            return true; // Encontró un hueco válido dentro del turno
                        }
                        // muevo inicio hasta el fin del bloqueo 
                        if ($bloqueo['fin'] > $clonInicio) {
                            $clonInicio = clone $bloqueo['fin'];
                        }
                    }
                    //Verificar el último hueco 
                    //debo verificar la ultima reserva talves no supera el final del turno
                    $minutosFinal = ($fin->getTimestamp() - $clonInicio->getTimestamp()) / 60;
                    if ($minutosFinal >= $servicio) {
                        return true;
                    }
                }

                // si no encontro hueco no esta disponible
                return false;
        }
        
        public function tieneDisponibilidad(Dia $dia): bool{
            //debo traeme los servicios
            $servicios = $this->repo->listarServicios();
            //recorro
            foreach ($servicios as $servicio) {
             $empleados = $this->repo->obtenerIdsEmpleadosPorServicio($servicio->getIdServicio());
             
                foreach ($empleados as $empleado) {
                    if ($this->disponibilidadEmpleadoDia($dia->getFecha(),$servicio->getDuracion(),(int)$empleado)) {
                        return true;
                    }
                }  
            }
            return false;
        }

        public function calendario(): array{
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

        public function disponibilidadServicios(string $dia): array{
            $servicios = $this->repo->listarServicios();

            foreach ($servicios as $servicio) {
                $empleados = $this->repo->obtenerIdsEmpleadosPorServicio($servicio->getIdServicio());
                $disponible = false;
                foreach ($empleados as $empleado) {
                    if ($this->disponibilidadEmpleadoDia($dia, $servicio->getDuracion(), (int)$empleado)) {
                        $disponible = true;
                        break;
                    }
                }  
                $servicio->setDisponible($disponible);
            } 
            return $servicios;
        }

        public function barberoServicioDisponiblePorDia(string $dia, int $idServicio):array{
            //nesesito el array de empleados
            $empleados = $this->repo->EmpleadosPorServicio($idServicio);
            //var_dump($empleados);
            foreach ($empleados as $empleado) {
                    $empleado->setDisponible($this->disponibilidadEmpleadoDia($dia,$idServicio,(int)$empleado->getId()));
            } 
            return $empleados;

        }
        public function horariosDiponiblesDia(string $dia, int $idServicio,int $idEmpleado):array{
                //Inicializamos el contenedor de resultados
                $resu=[];

                //traigo horarios del empleado (puede ser cortado o un turno completo)
                $horarios = $this->repo->horarioEmpleado($idEmpleado);
                //me traigo las reservas del empleado de tal dia
                $reservas = $this->repo->reservasPorFechaAEmpleado($dia, $idEmpleado);
                //me traigo el servicio
                $servicio=$this->repo->obtenerServicioPorId($idServicio);
                //me quedo con su duracion
                $duracionS=$servicio->getDuracion();
                
                foreach ($horarios as $horario) {
                    //comiezo horario laboral empleado
                    $inicio = new DateTime($horario->getHoraIni());
                    //fin del horario o fin del primer turno si es horario cortado
                    $fin    = new DateTime($horario->getHoraFin());
                    //fecha hoy
                    $hoy    = (new DateTime())->format('Y-m-d');

                    // Si la consulta es para el día de hoy entonce debo fijarme la hora actual
                    if ($dia === $hoy) {
                        $horaActual = new DateTime();
                        if ($horaActual > $inicio) {
                            $inicio = $horaActual;
                        }
                        // si tiene horario cortado y la hora actual ya supero el primer turno voy al otro turno
                        if ($inicio >= $fin) {
                            continue;//salto a sig horario osea siguiente turno
                        }
                    }

                    // guardo solo los bloqueso para el turno actual no me interesa otros
                    $bloqueosTurno = [];

                    //si horario no es cortado tiene descanzo me interesa tratarlo como un  bloqueo 
                    if (!empty($horario->getHoraIniDescanso()) && !empty($horario->getHoraFinDescanso()) &&
                        $horario->getHoraIniDescanso() !== "00:00:00" && $horario->getHoraFinDescanso() !== "00:00:00") {

                        $bloqueosTurno[] = ['inicio' => new DateTime($horario->getHoraIniDescanso()),'fin' => new DateTime($horario->getHoraFinDescanso())];
                    }
                    // Agrego las reservas que se superponen solo en el turno actual
                    foreach ($reservas as $reserva) {
                        $reservaInicio = new DateTime($reserva->getHoraIni());
                        $reservaFin    = new DateTime($reserva->getHoraFin());

                        // Una reserva pertenece o afecta a este turno si termina después del inicio del turno
                        // Y empieza antes de que el turno finalice
                        if ($reservaFin > $inicio && $reservaInicio < $fin) {
                            
                            // ajustamos
                            //si se cambia horario y ya exitia reserba anterior entonces esto lo arregla
                            //si el horario de la reserva es menor al inicio laboral entonces clono inicio sono queda con el de la reserva
                            $reservaInicioClamped = $reservaInicio < $inicio ? clone $inicio : $reservaInicio;
                            //lo mismo
                            $reservaFinClamped    = $reservaFin > $fin ? clone $fin : $reservaFin;

                            $bloqueosTurno[] = [
                                'inicio' => $reservaInicioClamped,
                                'fin'    => $reservaFinClamped
                            ];
                        }
                    }
                    // ordeno los bloqueo sentido horario
                    usort($bloqueosTurno, function ($a, $b) {
                        return $a['inicio'] <=> $b['inicio'];
                    });
                    //Buscar huecos libres entre los bloqueos del turno
                    $clonInicio = clone $inicio;

                   //busco los huecos posibles entre bloqueos
                    foreach ($bloqueosTurno as $bloqueo) {
                        
                        // CORRECCIÓN: Bucle while para sacar todos los turnos posibles de este hueco
                        while (true) {
                            $minutos = ($bloqueo['inicio']->getTimestamp() - $clonInicio->getTimestamp()) / 60;
                            
                            if ($minutos < $duracionS) {
                                break; // Ya no caben más servicios en este hueco
                            }

                            $horafin = clone $clonInicio;
                            $horafin->modify("+{$duracionS} minutes");

                            $resu[] = [
                                'inicio' => $clonInicio->format('H:i'),
                                'fin' => $horafin->format('H:i')
                            ];

                            // El cursor avanza al final del servicio recién agendado
                            $clonInicio = clone $horafin;
                        }

                        // Si el fin del bloqueo es mayor al cursor actual, movemos el cursor al final del bloqueo
                        if ($bloqueo['fin'] > $clonInicio) {
                            $clonInicio = clone $bloqueo['fin'];
                        }
                    }

                    //TODOS los huecos posibles desde el último bloqueo hasta el fin de jornada
                    //si no hay bloqueos esto calculara todo los turnos posibles del horario del empleado
                    while (true) {
                        $minutosFinal = ($fin->getTimestamp() - $clonInicio->getTimestamp()) / 60;
                        
                        if ($minutosFinal < $duracionS) {
                            break;
                        }

                        $horafin = clone $clonInicio;
                        $horafin->modify("+{$duracionS} minutes");

                        $resu[] = [
                            'inicio' => $clonInicio->format('H:i'),
                            'fin' => $horafin->format('H:i')
                        ];

                        $clonInicio = clone $horafin;
                    }
                }
                
               
                return $resu;
        }
    }
?>