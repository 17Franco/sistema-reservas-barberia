import { Component } from '@angular/core';
import { ResumenReserva } from "../../components/resumen-reserva/resumen-reserva";
import { Calendario } from "../../components/calendario/calendario";
import { ServiciosDisponibles } from "../../components/servicios-disponibles/servicios-disponibles";

import { EmpleadoDisponibles } from "../../components/empleado-disponibles/empleado-disponibles";
import { Dia, EmpleadoD, Horario, Servicio } from '../../interfaces/disponibilidad-interfaces';
import { HorarioDisponibles } from "../../components/horario-disponibles/horario-disponibles";


@Component({
  selector: 'app-reserva',
  imports: [ResumenReserva, Calendario, ServiciosDisponibles, EmpleadoDisponibles, HorarioDisponibles],
  templateUrl: './reserva.html',
  styleUrl: './reserva.scss',
})
export class Reserva {
  //variables para saber que ya los componente por lo menos tiene algo cargado
  reservaIniciada = false;
  servicioInicido=false;
  HorarioInicido=false;

  //variables / objetos que mando desde el padre a los otros componentes
  diaSeleccionado: Dia | null = null;

  ServicioSeleccionado: Servicio | null = null;

  EmpleadoSeleccionado: EmpleadoD | null = null;

  horarioSeleccionado: Horario | null = null;

  reset = false;

  resetPantalla() {
    this.reservaIniciada = false;
    this.servicioInicido = false;
    this.HorarioInicido = false;
    this.diaSeleccionado = null;
    this.ServicioSeleccionado = null;
    this.EmpleadoSeleccionado = null;
    this.horarioSeleccionado = null;
    this.reset = !this.reset;
  }

  //funciones donde cargo el objeto y borro seleccion anteriores
  
  onDia(dia:Dia) {
    this.diaSeleccionado = dia;
    this.reservaIniciada = true;
    this.ServicioSeleccionado = null;
    this.EmpleadoSeleccionado = null;
    this.horarioSeleccionado = null;
  }

  onServicio(servicio:Servicio) {
    this.servicioInicido=true;
    this.ServicioSeleccionado = servicio;
    this.EmpleadoSeleccionado = null;
    this.horarioSeleccionado = null;
  }

  onEmpleado(empleado:EmpleadoD) {
    this.HorarioInicido=true;
    this.EmpleadoSeleccionado = empleado;
    this.horarioSeleccionado = null;
  }
  onHorario(horario:Horario){
    this.horarioSeleccionado = horario;
  }
}
