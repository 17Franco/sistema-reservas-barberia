import { Component } from '@angular/core';
import { BarraGestionReserva } from '../../../components/componentesGestionReservas/barra-gestion-reserva/barra-gestion-reserva';
import { ListaGestionReserva } from "../../../components/componentesGestionReservas/lista-gestion-reserva/lista-gestion-reserva";
import { MenuFiltrosGestionReservas } from "../../../components/componentesGestionReservas/menu-filtros-gestion-reservas/menu-filtros-gestion-reservas";
@Component({
  selector: 'app-gestion-reserva',
  imports: [BarraGestionReserva, ListaGestionReserva, MenuFiltrosGestionReservas],
  templateUrl: './gestion-reserva.html',
  styleUrl: './gestion-reserva.scss',
})
export class GestionReserva {}
