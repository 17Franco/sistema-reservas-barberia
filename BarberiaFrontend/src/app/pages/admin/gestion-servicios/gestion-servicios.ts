import { Component, inject, OnInit, ChangeDetectorRef } from '@angular/core'; //agregue OnInit para cargar al iniciar, Agregue ChangeDetectorRef para detectar cambios después de cargar servicios.
import { NgFor, NgIf, NgClass } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { ServiciosService, Servicio } from '../../../services/servicios/servicio';

@Component({
  selector: 'app-gestion-servicios',
  imports: [NgFor, NgIf, NgClass, FormsModule],
  templateUrl: './gestion-servicios.html',
  styleUrl: './gestion-servicios.scss'
})
export class GestionServicios implements OnInit {

  private serviciosService = inject(ServiciosService);
  private cdr = inject(ChangeDetectorRef);

  servicios: Servicio[] = [];
  cargando = false;
  error: string | null = null;
  mensaje: string | null = null;

  modoEdicion = false;
  idEditando: number | null = null;

  servicioForm: Servicio = {
    nombre: '',
    descripcion: '',
    duracion: 30,
    precio: 0
  };

  //comentado porque ahora se carga al iniciar con ngOnInit.
  //constructor() {
  //  this.cargarServicios();
  //}

  ngOnInit() {
    this.cargarServicios();
  }

  cargarServicios() {
    this.cargando = true;
    this.error = null;

    this.serviciosService.getServicios().subscribe({
      next: (res) => {
        this.servicios = res.servicios || res;
        this.cargando = false;
        this.cdr.detectChanges(); // Detectar cambios despues de cargar servicios
      },
      error: (err) => {
        console.error(err);
        this.error = 'No se pudieron cargar los servicios.';
        this.cargando = false;

        this.cdr.detectChanges();
      }
    });
  }

  guardarServicio() {
    this.error = null;
    this.mensaje = null;

    if (!this.servicioForm.nombre || !this.servicioForm.descripcion || this.servicioForm.precio <= 0 || this.servicioForm.duracion <= 0) {
      this.error = 'Completá todos los campos correctamente.';
      return;
    }

    if (this.modoEdicion && this.idEditando) {
      this.serviciosService.actualizarServicio(this.idEditando, this.servicioForm).subscribe({
        next: () => {
          this.mensaje = 'Servicio actualizado correctamente.';
          this.resetFormulario();
          this.cargarServicios();
          this.cdr.detectChanges();
        },
        error: (err) => {
          console.error(err);
          this.error = 'No se pudo actualizar el servicio.';
        }
      });
    } else {
      this.serviciosService.crearServicio(this.servicioForm).subscribe({
        next: () => {
          this.mensaje = 'Servicio agregado correctamente.';
          this.resetFormulario();
          this.cargarServicios();
          this.cdr.detectChanges();
        },
        error: (err) => {
          console.error(err);
          this.error = 'No se pudo agregar el servicio.';
        }
      });
    }
  }

  editarServicio(servicio: Servicio) {
    this.modoEdicion = true;
    this.idEditando = servicio.idServicio || null;

    this.servicioForm = {
      nombre: servicio.nombre,
      descripcion: servicio.descripcion,
      duracion: servicio.duracion,
      precio: servicio.precio
    };

    this.cdr.detectChanges();
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }

  eliminarServicio(servicio: Servicio) {
    if (!servicio.idServicio) return;

    const confirmar = confirm(`¿Seguro que querés eliminar "${servicio.nombre}"?`);

    if (!confirmar) return;

    this.serviciosService.eliminarServicio(servicio.idServicio).subscribe({
      next: () => {
        this.mensaje = 'Servicio eliminado correctamente.';
        this.cargarServicios();
        this.cdr.detectChanges();
      },
      error: (err) => {
        console.error(err);
        this.error = 'No se pudo eliminar el servicio.';
        this.cdr.detectChanges();
      }
    });
  }

  cancelarEdicion() {
    this.resetFormulario();
  }

  resetFormulario() {
    this.modoEdicion = false;
    this.idEditando = null;

    this.servicioForm = {
      nombre: '',
      descripcion: '',
      duracion: 30,
      precio: 0
    };
  }
}