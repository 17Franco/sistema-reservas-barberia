import { Injectable, signal } from '@angular/core';

@Injectable({
  providedIn: 'root',
})
export class Filtros {

  filtros = signal({
  servicio: null,
  estado: null,
  empleado: null,
  fechaDesde: null,
  fechaHasta: null
});

totalReservas = signal<number>(0);


}
