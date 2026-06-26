import { Component, EventEmitter, Input, Output, signal } from '@angular/core';
import { Disponibilidad } from '../../services/disponibilidad/disponibilidad';
import { Dia, Servicio } from '../../interfaces/disponibilidad-interfaces';


@Component({
  selector: 'app-servicios-disponibles',
  imports: [],
  templateUrl: './servicios-disponibles.html',
  styleUrl: './servicios-disponibles.scss',
})
export class ServiciosDisponibles {
  constructor(private disponibilidad: Disponibilidad) {}

  startIndex = 0;
  readonly pageSize = 3;

  servicios = signal<Servicio[]>([]);

  @Output() ServicioSeleccionadoChange= new EventEmitter<Servicio>();

  ServicioSeleccionado: number| null = null;

  @Input() dia: Dia | null = null;
  
  @Input() reset: boolean | null = null;

  loadingServicios = signal<boolean>(false);

  ngOnChanges() {
    if (!this.servicios() || this.servicios().length === 0) {
      this.loadingServicios.set(true);
    }
    this.ServicioSeleccionado=null;
    this.startIndex = 0;
    if (this.dia?.fecha) {
    console.log(this.dia.fecha);
    this.disponibilidad.disponibilidadServicioDia(this.dia.fecha).subscribe({
      next:(res)=>{
      
        this.servicios.set(res);
        this.loadingServicios.set(false);
        console.log('Servicios SETEADOS:', this.servicios());
      },
      error:(err)=>{
        console.error('Error al traer los turnos', err);
        this.loadingServicios.set(false);
      }
    })
    }
  }

  serviciosVisbles() {
   return this.servicios().slice(this.startIndex, this.startIndex + this.pageSize);
  }
   next() {
    if (this.startIndex + this.pageSize < this.servicios().length) {
      this.startIndex += this.pageSize;
    }
  }
  prev() {
    if (this.startIndex > 0) {
      this.startIndex = Math.max(0, this.startIndex - this.pageSize);
    }
  }

  paginaActual(): number {
    return Math.floor(this.startIndex / this.pageSize) + 1;
  }

  totalPaginas(): number {
    return Math.max(1, Math.ceil(this.servicios().length / this.pageSize));
  }

  seleccionarServicio(servicio: Servicio) {
    this.ServicioSeleccionado = servicio.idServicio;
    this.ServicioSeleccionadoChange.emit(servicio);

  }
}
