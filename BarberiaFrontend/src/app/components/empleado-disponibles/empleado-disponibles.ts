import { Component, EventEmitter, Input, Output, signal } from '@angular/core';
import { Disponibilidad } from '../../services/disponibilidad/disponibilidad';
import { Dia, EmpleadoD, Servicio } from '../../interfaces/disponibilidad-interfaces';


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

  constructor(private disponibilidad: Disponibilidad) {}
  startIndex = 0;
  empleado = signal<EmpleadoD[]>([]);
  @Output() EmpleadoSeleccionadoChange= new EventEmitter<EmpleadoD>();
  EmpleadoSeleccionado: number| null = null;

   ngOnChanges() {
    this.EmpleadoSeleccionado=null;
    if (this.dia?.fecha && this.servicio?.idServicio) {
    console.log(this.dia.fecha && this.servicio.idServicio);
    this.disponibilidad.disponibilidadEmpleadoDia(this.dia.fecha,this.servicio.idServicio).subscribe({
      next:(res)=>{
      
        this.empleado.set(res);
        console.log('Servicios SETEADOS:', this.empleado());
      },
      error:(err)=>{
        console.error('Error al traer los turnos', err);
      }
    })
    }
  }

  empleadoVisbles() {
   return this.empleado().slice(this.startIndex, this.startIndex + 4);
  }
   next() {
    if (this.startIndex + 4 < this.empleado().length) {
      this.startIndex++;
    }
  }
  prev() {
    if (this.startIndex > 0) {
      this.startIndex--;
    }
  }

  seleccionarEmpleado(empleado: EmpleadoD) {
    this.EmpleadoSeleccionado = empleado.id;
    this.EmpleadoSeleccionadoChange.emit(empleado);

  }

  obtenerFoto(item:string): string {
  return 'http://localhost/sistema-reservas-barberia/ProyectoBarberiaBackend/public/' + item;
  }
}
