import { Component, EventEmitter, Input, Output, signal } from '@angular/core';
import { Disponibilidad } from '../../services/disponibilidad/disponibilidad';
import { Dia, EmpleadoD, Horario, Servicio } from '../../interfaces/disponibilidad-interfaces';

@Component({
  selector: 'app-horario-disponibles',
  imports: [],
  templateUrl: './horario-disponibles.html',
  styleUrl: './horario-disponibles.scss',
})
export class HorarioDisponibles {
  constructor(private disponibilidad: Disponibilidad) {}
  horarios = signal<Horario[]>([]);
  @Output() HorarioSeleccionadoChange= new EventEmitter<Horario>();
  HorarioSeleccionado: string| null = null;
  startIndex = 0;
  readonly pageSize = 7;

  @Input() dia: Dia | null = null;
  @Input() servicio: Servicio | null = null;
  @Input() empleado: EmpleadoD | null = null;
  @Input() reset: boolean | null = null;


  ngOnChanges() {
    this.HorarioSeleccionado=null;
    this.startIndex = 0;
    if (this.dia?.fecha && this.servicio?.idServicio && this.empleado?.id) {
    console.log(this.dia.fecha);
    this.disponibilidad.disponibilidadHorario(this.dia.fecha,this.servicio.idServicio,this.empleado.id).subscribe({
      next:(res)=>{
      
        this.horarios.set(res);
        console.log('Servicios SETEADOS:', this.horarios());
      },
      error:(err)=>{
        console.error('Error al traer los turnos', err);
      }
    })
    }
  }


  horarioVisbles() {
   return this.horarios().slice(this.startIndex, this.startIndex + this.pageSize);
  }
   next() {
    if (this.startIndex + this.pageSize < this.horarios().length) {
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
    return Math.max(1, Math.ceil(this.horarios().length / this.pageSize));
  }

  seleccionarHorario(horario: Horario) {
    this.HorarioSeleccionado= horario.inicio;
    this.HorarioSeleccionadoChange.emit(horario);

  }
}
