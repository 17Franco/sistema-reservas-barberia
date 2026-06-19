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
  servicios = signal<Servicio[]>([]);
  @Output() ServicioSeleccionadoChange= new EventEmitter<Servicio>();
  ServicioSeleccionado: number| null = null;

  @Input() dia: Dia | null = null;
  @Input() reset: boolean | null = null;

  ngOnChanges() {
    this.ServicioSeleccionado=null;
    if (this.dia?.fecha) {
    console.log(this.dia.fecha);
    this.disponibilidad.disponibilidadServicioDia(this.dia.fecha).subscribe({
      next:(res)=>{
      
        this.servicios.set(res);
        console.log('Servicios SETEADOS:', this.servicios());
      },
      error:(err)=>{
        console.error('Error al traer los turnos', err);
      }
    })
    }
  }

  serviciosVisbles() {
   return this.servicios().slice(this.startIndex, this.startIndex + 3);
  }
   next() {
    if (this.startIndex + 3 < this.servicios().length) {
      this.startIndex++;
    }
  }
  prev() {
    if (this.startIndex > 0) {
      this.startIndex--;
    }
  }

  seleccionarServicio(servicio: Servicio) {
    this.ServicioSeleccionado = servicio.idServicio;
    this.ServicioSeleccionadoChange.emit(servicio);

  }
}
