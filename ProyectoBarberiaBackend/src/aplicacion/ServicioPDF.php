<?php
    namespace Barberia\Backend\aplicacion;

use Barberia\Backend\dominio\Reserva;

    interface ServicioPDF {
         public function crearPDF(Reserva $reserva, int $idReserva): string;
    }
?>