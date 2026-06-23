import { Component, signal } from '@angular/core';
import { NgClass } from '@angular/common';
import { OnInit } from '@angular/core';
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

@Component({
  selector: 'app-lista-gestion-reserva',
  imports: [NgClass],
  templateUrl: './lista-gestion-reserva.html',
  styleUrl: './lista-gestion-reserva.scss',
})
export class ListaGestionReserva {
  paginasPorFecha: Record<string, number> = {};  //pagina en la que inicio de reservas
  public paginaDias = 1;
  public reservasPorPagina = 2;
  public reservasPorDia = 3;
  reservaPorFecha = signal<ReservasPorFecha[]>([]);

  ngOnInit(){
    this.cargarReservas();
    this.reservaPorFecha().forEach(dia => {
      this.paginasPorFecha[dia.fecha] = 1;
    });
  }
  cargarReservas(){
    this.reservaPorFecha.set( 
      [
          {
            fecha: "22 de junio de 2026",
            cantidadReservas: 3,
            reservas: [

              {
                idReserva: 1,
                cliente: {
                  id: 10,
                  nombre: "Juan Pérez",
                  telefono: "112345678",
                  email: "juan@gmail.com",
                  imagen: "img/usuario.png"
                },
                empleado: {
                  id: 1,
                  nombre: "Carlos Gómez"
                },
                servicio: {
                  id: 1,
                  nombre: "Corte de Pelo Tradicional"
                },
                horaInicio: "15:00",
                horaFin: "15:45",
                duracion: 45,
                estado: "CONFIRMADA"
              },
              {
                idReserva: 2,
                cliente: {
                  id: 10,
                  nombre: "Juan Pérez",
                  telefono: "112345678",
                  email: "juan@gmail.com",
                  imagen: "img/usuario.png"
                },
                empleado: {
                  id: 1,
                  nombre: "Carlos Gómez"
                },
                servicio: {
                  id: 1,
                  nombre: "Corte de Pelo Tradicional"
                },
                horaInicio: "15:00",
                horaFin: "15:45",
                duracion: 45,
                estado: "CONFIRMADA"
              },

              {
                idReserva: 3,
                cliente: {
                  id: 11,
                  nombre: "María González",
                  telefono: "113456789",
                  email: "maria@gmail.com",
                  imagen: "img/usuario.png"
                },
                empleado: {
                  id: 2,
                  nombre: "Ana Martínez"
                },
                servicio: {
                  id: 2,
                  nombre: "Corte + Barba"
                },
                horaInicio: "17:30",
                horaFin: "18:15",
                duracion: 45,
                estado: "CONFIRMADA"
              },

              {
                idReserva: 4,
                cliente: {
                  id: 12,
                  nombre: "Carlos Rodríguez",
                  telefono: "114567890",
                  email: "carlos@gmail.com",
                  imagen: "img/usuario.png"
                },
                empleado: {
                  id: 1,
                  nombre: "Carlos Gómez"
                },
                servicio: {
                  id: 3,
                  nombre: "Arreglo de Barba"
                },
                horaInicio: "19:00",
                horaFin: "19:30",
                duracion: 30,
                estado: "PENDIENTE"
              },
               {
                idReserva: 5,
                cliente: {
                  id: 12,
                  nombre: "Carlos Rodríguez",
                  telefono: "114567890",
                  email: "carlos@gmail.com",
                  imagen: "img/usuario.png"
                },
                empleado: {
                  id: 1,
                  nombre: "Carlos Gómez"
                },
                servicio: {
                  id: 3,
                  nombre: "Arreglo de Barba"
                },
                horaInicio: "19:00",
                horaFin: "19:30",
                duracion: 30,
                estado: "PENDIENTE"
              },
               {
                idReserva: 6,
                cliente: {
                  id: 12,
                  nombre: "Carlos Rodríguez",
                  telefono: "114567890",
                  email: "carlos@gmail.com",
                  imagen: "img/usuario.png"
                },
                empleado: {
                  id: 1,
                  nombre: "Carlos Gómez"
                },
                servicio: {
                  id: 3,
                  nombre: "Arreglo de Barba"
                },
                horaInicio: "19:00",
                horaFin: "19:30",
                duracion: 30,
                estado: "PENDIENTE"
              }
            ]
          },
          {
            fecha: "23 de junio de 2026",
            cantidadReservas: 3,
            reservas: [

              {
                idReserva: 1,
                cliente: {
                  id: 10,
                  nombre: "Juan Pérez",
                  telefono: "112345678",
                  email: "juan@gmail.com",
                  imagen: "img/usuario.png"
                },
                empleado: {
                  id: 1,
                  nombre: "Carlos Gómez"
                },
                servicio: {
                  id: 1,
                  nombre: "Corte de Pelo Tradicional"
                },
                horaInicio: "15:00",
                horaFin: "15:45",
                duracion: 45,
                estado: "CONFIRMADA"
              },
              {
                idReserva: 2,
                cliente: {
                  id: 10,
                  nombre: "Juan Pérez",
                  telefono: "112345678",
                  email: "juan@gmail.com",
                  imagen: "img/usuario.png"
                },
                empleado: {
                  id: 1,
                  nombre: "Carlos Gómez"
                },
                servicio: {
                  id: 1,
                  nombre: "Corte de Pelo Tradicional"
                },
                horaInicio: "15:00",
                horaFin: "15:45",
                duracion: 45,
                estado: "CONFIRMADA"
              },

              {
                idReserva: 3,
                cliente: {
                  id: 11,
                  nombre: "María González",
                  telefono: "113456789",
                  email: "maria@gmail.com",
                  imagen: "img/usuario.png"
                },
                empleado: {
                  id: 2,
                  nombre: "Ana Martínez"
                },
                servicio: {
                  id: 2,
                  nombre: "Corte + Barba"
                },
                horaInicio: "17:30",
                horaFin: "18:15",
                duracion: 45,
                estado: "CONFIRMADA"
              },

              {
                idReserva: 4,
                cliente: {
                  id: 12,
                  nombre: "Carlos Rodríguez",
                  telefono: "114567890",
                  email: "carlos@gmail.com",
                  imagen: "img/usuario.png"
                },
                empleado: {
                  id: 1,
                  nombre: "Carlos Gómez"
                },
                servicio: {
                  id: 3,
                  nombre: "Arreglo de Barba"
                },
                horaInicio: "19:00",
                horaFin: "19:30",
                duracion: 30,
                estado: "PENDIENTE"
              },
               {
                idReserva: 5,
                cliente: {
                  id: 12,
                  nombre: "Carlos Rodríguez",
                  telefono: "114567890",
                  email: "carlos@gmail.com",
                  imagen: "img/usuario.png"
                },
                empleado: {
                  id: 1,
                  nombre: "Carlos Gómez"
                },
                servicio: {
                  id: 3,
                  nombre: "Arreglo de Barba"
                },
                horaInicio: "19:00",
                horaFin: "19:30",
                duracion: 30,
                estado: "PENDIENTE"
              },
               {
                idReserva: 6,
                cliente: {
                  id: 12,
                  nombre: "Carlos Rodríguez",
                  telefono: "114567890",
                  email: "carlos@gmail.com",
                  imagen: "img/usuario.png"
                },
                empleado: {
                  id: 1,
                  nombre: "Carlos Gómez"
                },
                servicio: {
                  id: 3,
                  nombre: "Arreglo de Barba"
                },
                horaInicio: "19:00",
                horaFin: "19:30",
                duracion: 30,
                estado: "PENDIENTE"
              }
            ]
          },

          {
            fecha: "24 de junio de 2026",
            cantidadReservas: 2,
            reservas: [
              {
                idReserva: 6,
                cliente: {
                  id: 13,
                  nombre: "Martín López",
                  telefono: "115678901",
                  email: "martin@gmail.com",
                  imagen: "img/usuario.png"
                },
                empleado: {
                  id: 2,
                  nombre: "Ana Martínez"
                },
                servicio: {
                  id: 1,
                  nombre: "Corte Tradicional"
                },
                horaInicio: "10:00",
                horaFin: "10:45",
                duracion: 45,
                estado: "CONFIRMADA"
              },

              {
                idReserva: 7,
                cliente: {
                  id: 14,
                  nombre: "Lucas Ramírez",
                  telefono: "116789012",
                  email: "lucas@gmail.com",
                  imagen: "img/usuario.png"
                },
                empleado: {
                  id: 2,
                  nombre: "Ana Martínez"
                },
                servicio: {
                  id: 2,
                  nombre: "Corte + Barba"
                },
                horaInicio: "12:00",
                horaFin: "12:45",
                duracion: 45,
                estado: "CANCELADA"
              },
               {
                idReserva: 8,
                cliente: {
                  id: 14,
                  nombre: "Lucas Ramírez",
                  telefono: "116789012",
                  email: "lucas@gmail.com",
                  imagen: "img/usuario.png"
                },
                empleado: {
                  id: 2,
                  nombre: "Ana Martínez"
                },
                servicio: {
                  id: 2,
                  nombre: "Corte + Barba"
                },
                horaInicio: "12:00",
                horaFin: "12:45",
                duracion: 45,
                estado: "CANCELADA"
              },
               {
                idReserva: 9,
                cliente: {
                  id: 14,
                  nombre: "Lucas Ramírez",
                  telefono: "116789012",
                  email: "lucas@gmail.com",
                  imagen: "img/usuario.png"
                },
                empleado: {
                  id: 2,
                  nombre: "Ana Martínez"
                },
                servicio: {
                  id: 2,
                  nombre: "Corte + Barba"
                },
                horaInicio: "12:00",
                horaFin: "12:45",
                duracion: 45,
                estado: "CANCELADA"
              },
               {
                idReserva: 10,
                cliente: {
                  id: 14,
                  nombre: "Lucas Ramírez",
                  telefono: "116789012",
                  email: "lucas@gmail.com",
                  imagen: "img/usuario.png"
                },
                empleado: {
                  id: 2,
                  nombre: "Ana Martínez"
                },
                servicio: {
                  id: 2,
                  nombre: "Corte + Barba"
                },
                horaInicio: "12:00",
                horaFin: "12:45",
                duracion: 45,
                estado: "CANCELADA"
              },
              
            ]
          }
        ])
  }

  obtenerReservasPagina(dia: ReservasPorFecha): Reserva[] {
    const paginaActual = this.paginasPorFecha[dia.fecha];
    
    const inicio = (paginaActual - 1) * this.reservasPorDia ;
    const fin = inicio + this.reservasPorDia ;

    return dia.reservas.slice(inicio, fin);
  }

  get reservasVisibles(): ReservasPorFecha[] {
    const inicio = (this.paginaDias - 1) * this.reservasPorPagina;

    return this.reservaPorFecha().slice(
      inicio,
      inicio + this.reservasPorPagina,
    );
  }
    
  obtenerTotalPaginas(dia: ReservasPorFecha): number {
    return Math.ceil(dia.reservas.length / this.reservasPorDia );
  }

  get totalPaginasDias(): number {
    return Math.max(
      1,
      Math.ceil(this.reservaPorFecha().length / this.reservasPorPagina),
    );
  }
  getEstadoClass(estado: string): string {
  switch (estado) {
    case 'CONFIRMADA':
      return 'estado-confirmada';

    case 'CANCELADA':
      return 'estado-cancelada';

    case 'PENDIENTE':
      return 'estado-pendiente';

    default:
      return '';
  }
}


}
