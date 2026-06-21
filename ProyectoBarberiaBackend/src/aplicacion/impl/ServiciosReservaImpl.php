<?php
    namespace Barberia\Backend\aplicacion\impl;

use Barberia\Backend\aplicacion\ServicioEmail;
use Barberia\Backend\aplicacion\ServicioPDF;
use Barberia\Backend\aplicacion\ServiciosReserva;
use Barberia\Backend\dominio\repositorio\RepositorioReserva;
use Barberia\Backend\dominio\repositorio\RepositorioUsuario;
use Barberia\Backend\dominio\Reserva;
use Barberia\Backend\infraestructura\persistencia\ServicioRepositorioImpl;
use Barberia\Backend\dominio\EstadoReserva;
use DateInterval;
use DateTime;
use Exception;

class ServiciosReservaImpl implements ServiciosReserva {
        //variable donde guardo la interfaz repo
        private RepositorioUsuario $repoUsuario;
        private ServicioRepositorioImpl $repoServicio;
        private RepositorioReserva $repoReserva;
        private ServicioEmail $servicioEmail;
        private ServicioPDF $servicioPdf;

        //Inyección por constructo
        public function __construct(RepositorioUsuario $repoU,ServicioRepositorioImpl $repoS,RepositorioReserva $repoR,ServicioEmailImpl $email ,ServicioPDF $pdf) {
            
            $this->repoUsuario = $repoU;
            $this->repoServicio = $repoS;
            $this->repoReserva = $repoR;
            $this->servicioEmail = $email;
            $this->servicioPdf = $pdf;
        }

        public function crearBodyEmail(int $idReserva,Reserva $reserva, array $servicio):string{
            //gracias ia 
            return $body = '
                <div style="padding: 30px 10px; font-family: \'Segoe UI\', Helvetica, Arial, sans-serif; min-width: 320px;">
                    <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 500px; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.06); border: 1px solid #eef2f5;">
                        
                        <!-- Encabezado / Banner Superior -->
                        <tr>
                            <td style="background-color: #1a1a1a; padding: 35px 20px; text-align: center;">
                                <h1 style="color: #ffffff; margin: 0; font-size: 22px; font-weight: 600; letter-spacing: 0.5px; text-transform: uppercase;">
                                    ¡Reserva Confirmada! 💈
                                </h1>
                            </td>
                        </tr>

                        <tr>
                            <td style="padding: 30px 25px;">
                                <p style="margin: 0 0 20px 0; font-size: 16px; color: #495057; line-height: 1.5;">
                                    Hola, tu turno ha sido agendado con éxito. A continuación te compartimos los detalles de tu cita:
                                </p>

                                <!-- Tabla con detalles de la reserva -->
                                <table width="100%" style="border-collapse: collapse; margin-bottom: 25px; background-color: #fdfdfd; border-radius: 8px; border: 1px solid #f1f3f5;">
                                    <tr>
                                        <td style="padding: 12px 15px; border-bottom: 1px solid #f1f3f5; color: #6c757d; font-weight: 500; font-size: 14px;">Servicio:</td>
                                        <td style="padding: 12px 15px; border-bottom: 1px solid #f1f3f5; color: #1a1a1a; font-weight: 600; font-size: 14px; text-align: right;">' . htmlspecialchars($servicio["nombre"]) . '</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 12px 15px; border-bottom: 1px solid #f1f3f5; color: #6c757d; font-weight: 500; font-size: 14px;">Número de Reserva:</td>
                                        <td style="padding: 12px 15px; border-bottom: 1px solid #f1f3f5; color: #1a1a1a; font-weight: 600; font-size: 14px; text-align: right;">#' . $idReserva . '</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 12px 15px; border-bottom: 1px solid #f1f3f5; color: #6c757d; font-weight: 500; font-size: 14px;">Fecha:</td>
                                        <td style="padding: 12px 15px; border-bottom: 1px solid #f1f3f5; color: #1a1a1a; font-weight: 600; font-size: 14px; text-align: right;">' . $reserva->getFecha() . '</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 12px 15px; color: #6c757d; font-weight: 500; font-size: 14px;">Horario:</td>
                                        <td style="padding: 12px 15px; color: #1a1a1a; font-weight: 600; font-size: 14px; text-align: right;">' . $reserva->getHoraIni() . ' a ' . $reserva->getHoraFin() . '</td>
                                    </tr>
                                </table>

                                <p style="margin: 0; font-size: 14px; color: #868e96; text-align: center; line-height: 1.5;">
                                    Adjunto a este correo encontrarás tu comprobante digital en formato PDF.
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td style="background-color: #fafbfc; padding: 20px; text-align: center; border-top: 1px solid #eeeeee;">
                                <p style="margin: 0; font-size: 14px; color: #495057; font-weight: 500;">
                                    Gracias por confiar en nosotros 🙌
                                </p>
                            </td>
                        </tr>

                    </table>
                </div>
                ';
        }
        public function reservar(Reserva $reserva,string $email):int{
            //verificar usuario existe,servicio y empleado  
            if(!$this->repoUsuario->existeClientePorId($reserva->getIdCliente())){
                throw new Exception("El Usuario cliente no existe", 404);
            }

            if(!$this->repoUsuario->existeEmpleado($reserva->getIdEmpleado())){
                throw new Exception("El empleado no existe", 404);
            }

            $servicio =  $this->repoServicio->buscarServicio($reserva->getIdServicio());
           
            if($servicio === null){
                throw new Exception("El Servicio no existe", 404);
            }

            $duracionServicio = $servicio["duracion"];

            $fechaHoraReserva = new DateTime($reserva->getFecha() . " " . $reserva->getHoraIni());
            $fechaHoraActual = new DateTime();

            //que la fecha no sea pasada 
            if ($fechaHoraReserva < $fechaHoraActual) {
                throw new Exception("No se puede reservar una fecha u hora pasada", 400);
            }
            $horaInicio = new DateTime($reserva->getHoraIni());

            $horaFin = clone $horaInicio;
            $horaFin->modify("+{$duracionServicio} minutes");
            $reserva->setHoraFin($horaFin->format('H:i'));
            //Verificar que la reserva esté dentro del horario laboral del empleado.
            if(!$this->repoReserva->estaEnHorarioLaboralEmpleado($reserva->getIdEmpleado(),$reserva->getHoraIni(),$reserva->getHoraFin())){
                throw new Exception("El empleado no trabaja en ese horario", 400);
            }
            //verificar que no exista 
            if($this->repoReserva->existeReserva($reserva->getIdEmpleado(),$reserva->getFecha(),$reserva->getHoraIni(),$reserva->getHoraFin())){
                throw new Exception("Ya existe una reserva", 404);
            }
            //gurardo reserva
            $idReserva =$this->repoReserva->save($reserva);
            
            //si salio bien mando comprobante pdf por mail
            //$pdf = $this->servicioPdf->crearPDF($reserva,$idReserva);

            //$body = $this->crearBodyEmail($idReserva,$reserva,$servicio);

          
            //$this->servicioEmail->enviarConAdjunto($email,"Comprobante Reserva",$body,$pdf);

            return $idReserva;
        }

        public function enviarEmailComprobante(int $idReserva,int $idCliente){

            //busco reserva
            $reserva = $this->repoReserva->obtenerReserva($idReserva);

            if($reserva === null){
                throw new Exception("La reserva no existe", 404);
            }

            if ($reserva->getIdCliente() !== $idCliente) {
                throw new Exception("No tienes permiso para esta reserva", 403);
            }

            $servicio =  $this->repoServicio->buscarServicio($reserva->getIdServicio());

            if($servicio === null){
                throw new Exception("El servicio no existe", 404);
            }

            
            $emailCliente = $this->repoUsuario->getEmailById($idCliente);


            //$pdf = $this->servicioPdf->crearPDF($reserva,$idReserva);

            $body = $this->crearBodyEmail($idReserva,$reserva,$servicio);

            $this->servicioEmail->enviar($emailCliente,"Comprobante Reserva",$body);

        }





        public function cancelarReserva(int $idReserva, int $idCliente): void{
            $reserva = $this->repoReserva->obtenerReserva($idReserva);

            if ($reserva === null) {
                throw new Exception("La reserva no existe", 404);
            }

            if ($reserva->getIdCliente() !== $idCliente) {
                throw new Exception("No tienes permiso para cancelar esta reserva", 403);
            }

            if ($reserva->getEstadoReserva() !== EstadoReserva::PENDIENTE) {
                throw new Exception(
                    "Solo se pueden cancelar reservas pendientes",
                    409
                );
            }

            if (!$this->repoReserva->cancelar($idReserva, $idCliente)) {
                throw new Exception("No se pudo cancelar la reserva", 500);
            }
        }



        public function confirmarReserva(int $idReserva, int $idCliente): void{
            $reserva = $this->repoReserva->obtenerReserva($idReserva);

            if ($reserva === null) {
                throw new Exception("La reserva no existe", 404);
            }

            if ($reserva->getIdCliente() !== $idCliente) {
                throw new Exception(
                    "No tienes permiso para confirmar esta reserva",
                    403
                );
            }

            if ($reserva->getEstadoReserva() !== EstadoReserva::PENDIENTE) {
                throw new Exception(
                    "Solo se pueden confirmar reservas pendientes",
                    409
                );
            }

            if (!$this->repoReserva->confirmar($idReserva, $idCliente)) {
                throw new Exception("No se pudo confirmar la reserva", 500);
            }
        }




     }
?>
