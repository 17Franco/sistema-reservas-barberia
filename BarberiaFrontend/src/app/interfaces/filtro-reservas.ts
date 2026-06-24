export interface Reserva {
  idReserva: number;

  cliente: {
    id: number;
    nombre: string;
    telefono: string;
    email: string;
    imagen: string;
  };

  empleado: {
    id: number;
    nombre: string;
  };

  servicio: {
    id: number;
    nombre: string;
  };

  horaInicio: string;
  horaFin: string;
  duracion: number;
  estado: 'PENDIENTE' | 'CONFIRMADA' | 'CANCELADA' | 'COMPLETADA';
}

export interface ReservasPorFecha {
  fecha: string;
  cantidadReservas: number;
  reservas: Reserva[];
}

export interface ResponseReservas {
  success: boolean;
  totalReservas: number;
  data: ReservasPorFecha[];
}
