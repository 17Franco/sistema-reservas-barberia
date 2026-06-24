import { Component, inject } from '@angular/core';
import { RouterLink } from '@angular/router';
import { NgFor } from '@angular/common';

import { Auth } from '../../../services/auth';

@Component({
  selector: 'app-admin-dashboard',
  imports: [RouterLink, NgFor],
  templateUrl: './admin-dashboard.html',
  styleUrl: './admin-dashboard.scss'
})

export class AdminDashboard {
  filtrosService = inject(Auth);
  resumen = [
    {
      titulo: 'Reservas',
      valor: '0',
      descripcion: 'Reservas registradas en el sistema',
      ruta: '/admin/reservas'
    },
    {
      titulo: 'Servicios',
      valor: '5',
      descripcion: 'Servicios disponibles para clientes',
      ruta: '/admin/servicios'
    },
    {
      titulo: 'Barberos',
      valor:  '5',
      descripcion: 'Barberos activos registrados',
      ruta: '/admin/barberos'
    }
  ];

  accesos = [
    {
      titulo: 'Gestionar servicios',
      descripcion: 'Agregar, editar o eliminar servicios de la barbería.',
      boton: 'Ir a servicios',
      ruta: '/admin/servicios'
    },
    {
      titulo: 'Gestionar barberos',
      descripcion: 'Administrar barberos, especialidades y disponibilidad.',
      boton: 'Ir a barberos',
      ruta: '/admin/barberos'
    },
    {
      titulo: 'Gestionar reservas',
      descripcion: 'Visualizar reservas realizadas y cambiar su estado.',
      boton: 'Ir a reservas',
      ruta: '/admin/reservas'
    },
    {
      titulo: 'Gestionar servicios-barbero',
      descripcion: 'Administrar los servicios asociados a los barberos.',
      boton: 'Ir a servicios-barbero',
      ruta: '/admin/servicios-barbero'
    }
  ];
}