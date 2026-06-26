import { Component, EventEmitter, Input, Output, signal } from '@angular/core';
import { Disponibilidad } from '../../services/disponibilidad/disponibilidad';
import { Dia, EmpleadoD, Servicio } from '../../interfaces/disponibilidad-interfaces';
import { environment } from '../../../environments/environment';


@Component({
  selector: 'app-empleado-disponibles',
  imports: [],
  templateUrl: './empleado-disponibles.html',
  styleUrl: './empleado-disponibles.scss',
})
export class EmpleadoDisponibles {
  @Input() dia: Dia | null = null;
  @Input() servicio: Servicio | null = null;
  @Input() reset: boolean | null = null;
  loadingBarberos = signal<boolean>(false);
  constructor(private disponibilidad: Disponibilidad) {}
  startIndex = 0;
  readonly pageSize = 4;
  empleado = signal<EmpleadoD[]>([]);
  @Output() EmpleadoSeleccionadoChange= new EventEmitter<EmpleadoD>();
  EmpleadoSeleccionado: number| null = null;

   ngOnChanges() {
    if (!this.empleado() || this.empleado().length === 0) {
      this.loadingBarberos.set(true);
    }
    this.EmpleadoSeleccionado=null;
    this.startIndex = 0;
    if (this.dia?.fecha && this.servicio?.idServicio) {
    console.log(this.dia.fecha && this.servicio.idServicio);
    this.disponibilidad.disponibilidadEmpleadoDia(this.dia.fecha,this.servicio.idServicio).subscribe({
      next:(res)=>{
      
        this.empleado.set(res);
        this.loadingBarberos.set(false);
        console.log('Servicios SETEADOS:', this.empleado());
      },
      error:(err)=>{
        console.error('Error al traer los turnos', err);
        this.loadingBarberos.set(false);
      }
    })
    }
  }

  empleadoVisbles() {
   return this.empleado().slice(this.startIndex, this.startIndex + this.pageSize);
  }
   next() {
    if (this.startIndex + this.pageSize < this.empleado().length) {
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
    return Math.max(1, Math.ceil(this.empleado().length / this.pageSize));
  }

  seleccionarEmpleado(empleado: EmpleadoD) {
    this.EmpleadoSeleccionado = empleado.id;
    this.EmpleadoSeleccionadoChange.emit(empleado);

  }

  obtenerFoto(item:string): string {
  return `${environment.backendPublicUrl}/${item}`;
  }
}
