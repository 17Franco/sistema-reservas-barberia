import { Component, EventEmitter, inject, Input, OnInit, Output, signal } from '@angular/core';
import { Disponibilidad } from '../../services/disponibilidad/disponibilidad';
import { NgClass } from '@angular/common';
import { Dia } from '../../interfaces/disponibilidad-interfaces';

@Component({
  selector: 'app-calendario',
  imports: [NgClass],
  templateUrl: './calendario.html',
  styleUrl: './calendario.scss',
})
export class Calendario implements OnInit {
  constructor(private disponibilidad: Disponibilidad) {}

  @Output() diaSeleccionadoChange= new EventEmitter<Dia>();
  @Input() reset: boolean | null = null;

  dias = signal<Dia[]>([]);
  diaSeleccionado: string | null = null;
  startIndex = 0;
  ngOnInit(): void {
    this.disponibilidad.calendario().subscribe({
      next:(res)=>{
      
        this.dias.set(res);
        //console.log('DIAS SETEADOS:', this.dias);
      },
      error:(err)=>{
        console.error('Error al traer los turnos', err);
      }
    })
  }

  
  diasVisible() {
   return this.dias().slice(this.startIndex, this.startIndex + 7);
  }
  next() {
    if (this.startIndex + 7 < this.dias().length) {
      this.startIndex++;
    }
  }
  prev() {
    if (this.startIndex > 0) {
      this.startIndex--;
    }
  }
  seleccionarDia(dia: Dia) {
    this.diaSeleccionado = dia.fecha;
    this.diaSeleccionadoChange.emit(dia);

  }
}
