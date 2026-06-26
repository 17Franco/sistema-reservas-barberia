import { Component, effect, inject } from '@angular/core';
import { Filtros } from '../../../services/filtros';

@Component({
  selector: 'app-barra-gestion-reserva',
  imports: [],
  templateUrl: './barra-gestion-reserva.html',
  styleUrl: './barra-gestion-reserva.scss',
})
export class BarraGestionReserva {

  filtrosService = inject(Filtros);
  
  
}
