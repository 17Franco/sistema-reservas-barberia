import { Component,inject,OnInit, signal } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { ServiciosService } from '../../../services/servicios/servicio';
import { Auth } from '../../../services/auth';
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
  private serviciosService = inject(ServiciosService);
  private authSer = inject(Auth);
  cargando = false;
  error: string | null = null;
  mensaje: string | null = null;

  servicios = signal<Servicio[]>([]);
  //servicios: Servicio[] = [];
  barberos = signal<Barbero[]>([]);


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
     this.cargarServicios();
     this.cargarBarberos();
     this.cargarReservas();
  }
  cargarReservas(){
    
  }
  cargarBarberos(){
    this.error = null;
    this.authSer.getBarberos().subscribe({
      next: (res)=>{
        this.barberos.set(res.empleados || res);
      },
      error: (err)=>{
        console.error(err);
        this.error = 'No se pudieron cargar los Barberos.';
      }
    })
  }
  cargarServicios() {
    //this.cargando = true;
    //this.error = null;

    this.serviciosService.getServicios().subscribe({
      next: (res) => {
        this.servicios.set(res.servicios || res);
        //this.cargando = false;
        
      },
      error: (err) => {
        console.error(err);
        this.error = 'No se pudieron cargar los servicios.';
        //this.cargando = false;
      }
    });
  }
  cambio() {
      /*console.log('Servicio seleccionado:', this.filtros.servicio);
      console.log('Servicio seleccionado:', this.filtros.empleado);
      console.log('Servicio seleccionado:', this.filtros.estado);
      console.log('Servicio seleccionado:', this.filtros.fechaDesde);
      console.log('Servicio seleccionado:', this.filtros.fechaHasta);*/
    }

    limpiar(){
      this.filtros = {
          servicio: null,
          estado: null,
          empleado: null,
          fechaDesde: null,
          fechaHasta: null
        };

     // this.cargarDatos(); // o tu función que vuelve a listar sin filtros
    }
    
}
