import { Component, OnInit, inject } from '@angular/core';
import { RouterLink } from '@angular/router';
import { NgFor } from '@angular/common';
import { ResenasService } from '../../../services/resenas.service';
import { Auth } from '../../../services/auth';

@Component({
  selector: 'app-admin-dashboard',
  imports: [RouterLink, NgFor],
  templateUrl: './admin-dashboard.html',
  styleUrl: './admin-dashboard.scss'
})
export class AdminDashboard implements OnInit {

  filtrosService = inject(Auth);
  private resenasService = inject(ResenasService);

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
      valor: '5',
      descripcion: 'Barberos activos registrados',
      ruta: '/admin/barberos'
    },
    {
      titulo: 'Reseñas',
      valor: '0',
      descripcion: 'Calificaciones registradas por clientes',
      ruta: '/admin/resenas'
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
    },
    {
      titulo: 'Gestionar reseñas',
      descripcion: 'Ver calificaciones de clientes y registrar reseñas de prueba.',
      boton: 'Ir a reseñas',
      ruta: '/admin/resenas'
    }
  ];

  ngOnInit(): void {
    this.cargarTotalResenas();
  }

  cargarTotalResenas(): void {
    this.resenasService.listarResenas().subscribe({
      next: (res: any) => {
        console.log('Reseñas dashboard:', res);

        let cantidad = 0;

        if (Array.isArray(res)) {
          cantidad = res.length;
        } else if (Array.isArray(res.resenas)) {
          cantidad = res.resenas.length;
        } else if (Array.isArray(res.data)) {
          cantidad = res.data.length;
        } else if (Array.isArray(res.mensaje)) {
          cantidad = res.mensaje.length;
        }

        const tarjetaResenas = this.resumen.find(item => item.titulo === 'Reseñas');

        if (tarjetaResenas) {
          tarjetaResenas.valor = cantidad.toString();
        }
      },
      error: (err) => {
        console.error('Error cargando total de reseñas:', err);

        const tarjetaResenas = this.resumen.find(item => item.titulo === 'Reseñas');

        if (tarjetaResenas) {
          tarjetaResenas.valor = '0';
        }
      }
    });
  }
}