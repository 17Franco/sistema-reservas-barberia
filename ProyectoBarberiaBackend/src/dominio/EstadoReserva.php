<?php
namespace Barberia\Backend\dominio;
enum EstadoReserva: string {
    case PENDIENTE = 'PENDIENTE';
    case CONFIRMADA = 'CONFIRMADA';
    case CANCELADA = 'CANCELADA';
    case COMPLETADA = 'COMPLETADA';
}

?> 


