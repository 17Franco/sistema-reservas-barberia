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
  hasOpenedServicio = false;
  hasOpenedEmpleado = false;
  hasOpenedHorario = false;

  //variables / objetos que mando desde el padre a los otros componentes
  diaSeleccionado: Dia | null = null;

  ServicioSeleccionado: Servicio | null = null;

  EmpleadoSeleccionado: EmpleadoD | null = null;

  horarioSeleccionado: Horario | null = null;

  reset = false;
  
  pasoActivo: 'dia' | 'servicio' | 'empleado' | 'horario' = 'dia';
  
  resetPantalla() {
    

    this.diaSeleccionado = null;
    this.ServicioSeleccionado = null;
    this.EmpleadoSeleccionado = null;
    this.horarioSeleccionado = null;
    this.pasoActivo = 'dia';
    this.reset = !this.reset;
  }

  //funciones donde cargo el objeto y borro seleccion anteriores
  
  onDia(dia:Dia) {
    this.diaSeleccionado = dia;
    this.hasOpenedServicio = true;

    this.ServicioSeleccionado = null;
    this.EmpleadoSeleccionado = null;
    this.horarioSeleccionado = null;

     this.pasoActivo = 'servicio';
   
  }

  onServicio(servicio:Servicio) {
    this.ServicioSeleccionado = servicio;
    this.hasOpenedEmpleado = true;
    this.EmpleadoSeleccionado = null;
    this.horarioSeleccionado = null;

     this.pasoActivo = 'empleado';
   
  }

  onEmpleado(empleado:EmpleadoD) {
    this.EmpleadoSeleccionado = empleado;
    this.hasOpenedHorario = true;
    this.horarioSeleccionado = null;
     this.pasoActivo = 'horario';
  }
  onHorario(horario:Horario){
    this.horarioSeleccionado = horario;
  }
}
