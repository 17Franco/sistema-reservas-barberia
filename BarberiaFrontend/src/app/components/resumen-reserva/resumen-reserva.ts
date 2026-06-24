import { Component, EventEmitter, Input, Output } from '@angular/core';
import { Dia, EmpleadoD, Horario, Servicio } from '../../interfaces/disponibilidad-interfaces';
import { Disponibilidad, Reserva } from '../../services/disponibilidad/disponibilidad';
import Swal from 'sweetalert2';
import { environment } from '../../../environments/environment';


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
  @Input() reset: boolean | null = null;

  @Output() reservaCreada = new EventEmitter<boolean>();
  constructor(private disponibilidad: Disponibilidad) {}

  obtenerFoto(item?:string): string {
    return `${environment.backendPublicUrl}/${item}`;
  }

  reservar(){
    if (!this.empleado || !this.servicio || !this.dia || !this.horario) {
      return;
    }
    
    const reserva: Reserva = {
      idEmpleado: this.empleado.id,
      idServicio: this.servicio.idServicio,
      fecha: this.dia.fecha,
      horaIni: this.horario.inicio
    };
    console.log(reserva);
    this.disponibilidad.reservar(reserva).subscribe({
    next: (res: any) => {
      const idReserva = res.idReserva;
      Swal.fire({
        title: 'Reserva creada',
        text: 'La reserva fue registrada correctamente. Se envio un comprobante por correo.',
        icon: 'success'
      }).then( (result) =>{
        if(result.isConfirmed){
          this.reservaCreada.emit(true);
          this.dia=null;
          this.servicio=null;
          this.empleado=null;
          this.horario=null;
        }
      })
      this.disponibilidad.eviarComprobante(idReserva).subscribe();
    },
    error: (err) => {
        if (err.status === 409) {
        Swal.fire({
          title: 'Horario no disponible',
          text: 'Ya existe una reserva en ese horario',
          icon: 'warning'
        });
        return;
      }

      Swal.fire({
        title: 'Error',
        text: 'No se pudo crear la reserva',
        icon: 'error'
      });

      console.error('Error al crear la reserva', err);
    }
    });

}
    


  habilitoReserva(){
    return (this.dia == null || this.servicio == null || this.empleado == null || this.horario == null);
  }
}
