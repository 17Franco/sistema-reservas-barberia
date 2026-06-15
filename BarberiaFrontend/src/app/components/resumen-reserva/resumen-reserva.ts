import { Component, Input } from '@angular/core';
import { Dia, EmpleadoD, Horario, Servicio } from '../../interfaces/disponibilidad-interfaces';


@Component({
  selector: 'app-resumen-reserva',
  imports: [],
  templateUrl: './resumen-reserva.html',
  styleUrl: './resumen-reserva.scss',
})
export class ResumenReserva {
  @Input() dia: Dia | null = null;
  @Input() servicio: Servicio | null = null;
  @Input() empleado: EmpleadoD | null = null;
  @Input() horario: Horario | null = null;
  
  obtenerFoto(item?:string): string {
    return 'http://localhost/sistema-reservas-barberia/ProyectoBarberiaBackend/public/' + item;
  }

}
