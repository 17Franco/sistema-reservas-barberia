<?php
   namespace Barberia\Backend\aplicacion;

   use Barberia\Backend\dominio\Reserva;

   interface ServiciosReserva {

      public function reservar(Reserva $reserva,string $email):int;
      public function enviarEmailComprobante(int $idReserva,int $idCliente);
      public function cancelarReserva(int $idReserva, int $idCliente): void;
      public function confirmarReserva(int $idReserva, int $idCliente): void;
   }
?>
