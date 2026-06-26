import { Component, EventEmitter, inject, Input, OnInit, Output, signal } from '@angular/core';
import { Disponibilidad } from '../../services/disponibilidad/disponibilidad';
import { NgClass } from '@angular/common';
import { Dia } from '../../interfaces/disponibilidad-interfaces';

@Component({
  selector: 'app-calendario',
  imports: [],
  templateUrl: './calendario.html',
  styleUrl: './calendario.scss',
})
export class Calendario implements OnInit {
  //injecto servicio para realizar las peticiones
  constructor(private disponibilidad: Disponibilidad) {}

  //variable donde guardo  el dia que selecciona el usuario que emito al padre (reserva)
  @Output() diaSeleccionadoChange= new EventEmitter<Dia>();

  @Input() reset: boolean | null = null;

  //variable donde guardo los dias que traigo de la bd
  dias = signal<Dia[]>([]);

  //guardo fecha
  diaSeleccionado: string | null = null;

  loadingDias = signal<boolean>(false);
  //guardo un index para saber desde donde empiezo a mostrar el arreglo de dias en el front
  startIndex = 0;
  //carga al inicio
  ngOnInit(): void {
    this.loadingDias.set(true);
    //peticion
    this.disponibilidad.calendario().subscribe({
      next:(res)=>{
        //cargo los dias en dias con signal detecta que cambio y actualiza
        this.dias.set(res);
        this.loadingDias.set(false);
        //console.log('DIAS SETEADOS:', this.dias);
      },
      error:(err)=>{
        console.error('Error al traer los turnos', err);
        this.loadingDias.set(false);
      }
    })
  }

  ngOnChanges(): void {
    this.diaSeleccionado = null;
    this.startIndex = 0;
  }

  //mue devuelve 7 dias visibles (acorto el array)
  diasVisible() {
   return this.dias().slice(this.startIndex, this.startIndex + 7);
  }
  //muevo un dia hacia adelante
  next() {
    if (this.startIndex + 7 < this.dias().length) {
      this.startIndex++;
    }
  }
  //muevo un dia hacia atras
  prev() {
    if (this.startIndex > 0) {
      this.startIndex--;
    }
  }

  //cargo y emito el dia seleccionado 
  seleccionarDia(dia: Dia) {
    this.diaSeleccionado = dia.fecha;
    this.diaSeleccionadoChange.emit(dia);

  }
}
