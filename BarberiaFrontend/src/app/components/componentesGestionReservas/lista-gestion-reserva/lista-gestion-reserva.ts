import { Component, effect, EventEmitter, inject, Input, Output, signal } from '@angular/core';
import { NgClass } from '@angular/common';
import { OnInit } from '@angular/core';
import { Reserva, ReservasPorFecha } from '../../../interfaces/filtro-reservas';
import { Auth } from '../../../services/auth';
import { Filtros } from '../../../services/filtros';
import { environment } from '../../../../environments/environment';
import Swal from 'sweetalert2';




@Component({
  selector: 'app-lista-gestion-reserva',
  imports: [NgClass],
  templateUrl: './lista-gestion-reserva.html',
  styleUrl: './lista-gestion-reserva.scss',
})
export class ListaGestionReserva {
  authService = inject(Auth);
  filtrosService = inject(Filtros);
  paginasPorFecha: Record<string, number> = {}; //guardo en que pagina estoy en la paginacion de reservas de cada dia 
  public paginaDias = 1;//empiezo en la pagina uno 
  public reservasPorPagina = 1; //aca la cantidad de dias que muestro en cada paginacion
  public reservasPorDia = 4; //aca la cantidad de reserva que muestro por cada dia
  reservaPorFecha = signal<ReservasPorFecha[]>([]);
  //@Output() totalChange = new EventEmitter<number>();//para pasarle el total a el padre y de ahi mandarlo a barra gestion reserva
  refresh = signal(0);
constructor() {
  effect(() => {
    //this.refresh();
    this.paginaDias=1;//por cada effecvuelvo a pagina 1 
    const filtros = this.filtrosService.filtros();

    this.cargarReservas(filtros);
    
  });
}

  cargarReservas(filtros:any){
      this.authService.filtrarReservas(filtros).subscribe({
        next:(res)=>{
            if(res.success){
              console.log(res);
              this.filtrosService.totalReservas.set(res.totalReservas);
              this.reservaPorFecha.set(res.data);
                this.reservaPorFecha().forEach(dia => {
                  if (this.paginasPorFecha[dia.fecha] === undefined ) {
                    this.paginasPorFecha[dia.fecha] = 1;
                  }
                });
              
            }
        },
        error:(err)=>{
          console.log(err);
        }
      })      
  }

  
  avanzarPaginaReservaPorFecha(){
    this.paginaDias = this.paginaDias + 1;
    this.reservaPorFecha().forEach(dia => {
        this.paginasPorFecha[dia.fecha] = 1;
    });
  }

  retrocederPaginaReservaPorFecha(){
    this.paginaDias = this.paginaDias - 1;
     this.reservaPorFecha().forEach(dia => {
        this.paginasPorFecha[dia.fecha] = 1;
    });
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

    case 'COMPLETADA':
      return 'estado-completado';

    default:
      return '';
  }
}


async cambiarEstado(id: number, estado: string) {

  const ok = await Swal.fire({
        title: '¿Seguro que quieres actualizar el estado de la reserva?',
        text: 'Esta acción no se puede revertir.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, Actualizar',
        cancelButtonText: 'Volver',
    });

  if (!ok.isConfirmed) return;

  let request$;

  if (estado === 'CANCELADA') {
    request$ = this.authService.cancelarReserva(id);
  }

  if (estado === 'CONFIRMADA') {
    request$ = this.authService.confirmarReserva(id);
  }

  if (estado === 'COMPLETADA') {
    request$ = this.authService.completarReserva(id);
  }

  request$?.subscribe({
    next: () => {
      Swal.fire('OK', 'Estado actualizado', 'success');
      this.recargar(); 
    },
    error: () => {
      Swal.fire('Error', 'No se pudo actualizar', 'error');
    }
  });
}

private recargar() {
  const filtros = this.filtrosService.filtros();
  this.cargarReservas(filtros);
}
getImagen(url: string) {
  return `${environment.backendPublicUrl}${url}`;
}


}
