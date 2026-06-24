import { Component, effect, EventEmitter, inject, Input, Output, signal } from '@angular/core';
import { NgClass } from '@angular/common';
import { OnInit } from '@angular/core';
import { Reserva, ReservasPorFecha } from '../../../interfaces/filtro-reservas';
import { Auth } from '../../../services/auth';
import { Filtros } from '../../../services/filtros';
import { environment } from '../../../../environments/environment';




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

  constructor() {
  effect(() => {
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
                this.paginasPorFecha[dia.fecha] = 1;
              });
            }
        },
        error:(err)=>{
          console.log(err);
        }
      })

        
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

getImagen(url: string) {
  return `${environment.apiUrlImg}${url}`;
}


}
