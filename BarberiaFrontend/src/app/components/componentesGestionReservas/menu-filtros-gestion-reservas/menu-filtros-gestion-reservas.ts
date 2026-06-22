import { Component,OnInit } from '@angular/core';
import { FormsModule } from '@angular/forms';
interface Barbero {
  id?: number;
  ci: string;
  nombre: string;
  apellido: string;
  fechaNac: string;
  password: string;
  email: string;
  celular: string;
  estado: 'ACTIVO' | 'INACTIVO';
  idEspecialidad: number;
  especialidad?: string;
  foto?: string;
}

export interface Servicio {
  idServicio?: number;
  nombre: string;
  descripcion: string;
  duracion: number;
  precio: number;
} 

@Component({
  selector: 'app-menu-filtros-gestion-reservas',
  imports: [FormsModule],
  templateUrl: './menu-filtros-gestion-reservas.html',
  styleUrl: './menu-filtros-gestion-reservas.scss',
})



export class MenuFiltrosGestionReservas {

  barberos: Barbero[] = [];
  servicios: Servicio[] = [];
  estados = [
    { nombre: 'Pendiente' },
    { nombre: 'Confirmada' },
    { nombre: 'Cancelada' },
    { nombre: 'Completada' }
  ];

  filtros = {
    servicio: null,
    estado: null,
    empleado: null,
    fechaDesde:null,
    fechaHasta:null
  };

  ngOnInit(){
    
      this.servicios = [
    {
      idServicio: 1,
      nombre: 'Corte clásico',
      descripcion: 'Corte tradicional con tijera y máquina',
      duracion: 30,
      precio: 500
    },
    {
      idServicio: 2,
      nombre: 'Degradado',
      descripcion: 'Corte con fade bajo, medio o alto',
      duracion: 45,
      precio: 700
    },
    {
      idServicio: 3,
      nombre: 'Arreglo de barba',
      descripcion: 'Perfilado y arreglo completo de barba',
      duracion: 20,
      precio: 350
    },
    {
      idServicio: 4,
      nombre: 'Corte + barba',
      descripcion: 'Servicio completo de corte y barba',
      duracion: 60,
      precio: 900
    },
    {
      idServicio: 5,
      nombre: 'Tratamiento capilar',
      descripcion: 'Lavado, hidratación y cuidado del cabello',
      duracion: 40,
      precio: 800
    }
  ];
    
  }

  cambio() {
      console.log('Servicio seleccionado:', this.filtros.servicio);
    }
}
