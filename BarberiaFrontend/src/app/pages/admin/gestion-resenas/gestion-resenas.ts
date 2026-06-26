import { Component, inject, OnInit, ChangeDetectorRef } from '@angular/core';
import { NgFor, NgIf } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { HttpClient } from '@angular/common/http';
import { ResenasService, Resena } from '../../../services/resenas.service';
import { environment } from '../../../../environments/environment';

import { Auth } from '../../../services/auth';

interface Barbero {
  id?: number;
  id_usuario?: number;
  idEmpleado?: number;
  nombre: string;
  apellido: string;
  email?: string;
  estado?: string;
  especialidad?: string;
  foto?: string;
}

@Component({
  selector: 'app-gestion-resenas',
  standalone: true,
  imports: [NgFor, NgIf, FormsModule],
  templateUrl: './gestion-resenas.html',
  styleUrl: './gestion-resenas.scss'
})
export class GestionResenas implements OnInit {

  private resenasService = inject(ResenasService);
  private http = inject(HttpClient);
  private cdr = inject(ChangeDetectorRef);

  private auth = inject(Auth);

  usuarioActualId: number | null = null;

  apiUrl = environment.apiUrl;

  barberos: Barbero[] = [];
  resenas: Resena[] = [];

  cargando = false;
  error: string | null = null;
  mensaje: string | null = null;

  modalAbierto = false;
  barberoSeleccionado: Barbero | null = null;
  modalResenasAbierto = false;
  barberoResenasSeleccionado: Barbero | null = null;
  tipoUsuarioActual = null;
  resenaForm: Resena = {
    idEmpleado: 0,
    puntuacion: 5,
    comentario: ''
  };


  ngOnInit() {
    this.cargarUsuarioActual();
    this.cargarBarberos();
    this.cargarResenas();
  }

cargarUsuarioActual() {
  this.auth.me().subscribe({
    next: (res: any) => {
      console.log('Usuario actual:', res);
      this.tipoUsuarioActual=res.tipo;
      this.usuarioActualId = Number(
        res.id ||
        res.usuario_id ||
        res.usuario?.id ||
        res.user?.id ||
        res.data?.id ||
        0
      );

      console.log('ID usuario actual:', this.usuarioActualId);

      this.cdr.detectChanges();
    },
    error: (err) => {
      console.error('Error obteniendo usuario actual:', err);
      this.usuarioActualId = null;
    }
  });
}

obtenerTipoUsuarioActual(){
  
}
misResenas(): Resena[] {
  if (!this.usuarioActualId) {
    return [];
  }

  return this.resenas.filter(resena =>
    Number(resena.idCliente) === this.usuarioActualId
  );
}

abrirModalVerResenas(barbero: Barbero, event: Event) {
  event.stopPropagation();

  this.barberoResenasSeleccionado = barbero;
  this.modalResenasAbierto = true;
}

cerrarModalVerResenas() {
  this.modalResenasAbierto = false;
  this.barberoResenasSeleccionado = null;
}

resenasDelBarberoSeleccionado(): Resena[] {
  if (!this.barberoResenasSeleccionado) {
    return [];
  }

  const idBarbero = this.obtenerIdBarbero(this.barberoResenasSeleccionado);

  return this.resenas.filter(resena =>
    Number(resena.idEmpleado) === idBarbero
  );
}

  cargarBarberos() {
    this.http.get<any>(`${this.apiUrl}/empleados`, { withCredentials: true })
      .subscribe({
        next: (res) => {
          console.log('Respuesta empleados:', res);

          if (Array.isArray(res)) {
            this.barberos = res;
          } else if (Array.isArray(res.empleados)) {
            this.barberos = res.empleados;
          } else if (Array.isArray(res.data)) {
            this.barberos = res.data;
          } else {
            this.barberos = [];
          }

          this.cdr.detectChanges();
        },
        error: (err) => {
          console.error('Error cargando barberos:', err);
          this.error = 'No se pudieron cargar los barberos.';
          this.cdr.detectChanges();
        }
      });
  }

  cargarResenas() {
    this.cargando = true;
    this.error = null;

    this.resenasService.listarResenas().subscribe({
      next: (res) => {
        console.log('Respuesta reseñas:', res);

        if (Array.isArray(res)) {
          this.resenas = res;
        } else if (Array.isArray(res.resenas)) {
          this.resenas = res.resenas;
        } else if (Array.isArray(res.data)) {
          this.resenas = res.data;
        } else {
          this.resenas = [];
        }

        this.cargando = false;
        this.cdr.detectChanges();
      },
      error: (err) => {
        console.error('Error cargando reseñas:', err);
        this.error = 'No se pudieron cargar las reseñas.';
        this.cargando = false;
        this.cdr.detectChanges();
      }
    });
  }

  obtenerIdBarbero(barbero: Barbero): number {
    return Number(barbero.id || barbero.id_usuario || barbero.idEmpleado || 0);
  }

  obtenerResenasBarbero(barbero: Barbero): Resena[] {
    const idBarbero = this.obtenerIdBarbero(barbero);

    return this.resenas.filter(resena =>
      Number(resena.idEmpleado) === idBarbero
    );
  }

  cantidadResenas(barbero: Barbero): number {
    return this.obtenerResenasBarbero(barbero).length;
  }

  promedioBarbero(barbero: Barbero): number {
    const resenasBarbero = this.obtenerResenasBarbero(barbero);

    if (resenasBarbero.length === 0) {
      return 0;
    }

    const total = resenasBarbero.reduce((suma, resena) => {
      return suma + Number(resena.puntuacion);
    }, 0);

    return total / resenasBarbero.length;
  }

  promedioTexto(barbero: Barbero): string {
    const promedio = this.promedioBarbero(barbero);

    if (promedio === 0) {
      return 'Sin puntuar';
    }

    return promedio.toFixed(1);
  }

  estrellasPromedio(barbero: Barbero): string {
    const promedio = Math.round(this.promedioBarbero(barbero));

    if (promedio <= 0) {
      return '☆☆☆☆☆';
    }

    return '⭐'.repeat(promedio) + '☆'.repeat(5 - promedio);
  }

abrirModalResena(barbero: Barbero, event?: Event) {
  if (event) {
    event.stopPropagation();
  }

  this.barberoSeleccionado = barbero;

  this.resenaForm = {
    idEmpleado: this.obtenerIdBarbero(barbero),
    puntuacion: 5,
    comentario: ''
  };

  this.error = null;
  this.mensaje = null;
  this.modalAbierto = true;
}

  cerrarModal() {
    this.modalAbierto = false;
    this.barberoSeleccionado = null;

    this.resenaForm = {
      idEmpleado: 0,
      puntuacion: 5,
      comentario: ''
    };
  }

  crearResena() {
    this.error = null;
    this.mensaje = null;

    if (!this.resenaForm.idEmpleado || this.resenaForm.idEmpleado === 0) {
      this.error = 'Seleccioná un barbero.';
      return;
    }

    if (this.resenaForm.puntuacion < 1 || this.resenaForm.puntuacion > 5) {
      this.error = 'La puntuación debe ser entre 1 y 5.';
      return;
    }

    this.resenasService.crearResena(this.resenaForm).subscribe({
      next: (res) => {
        if (res.success === false) {
          this.error = res.error || 'No se pudo crear la reseña.';
          return;
        }

        this.mensaje = 'Reseña creada correctamente.';

        this.cerrarModal();
        this.cargarResenas();
      },
      error: (err) => {
        console.error('Error creando reseña:', err);
        this.error = err.error?.error || 'No se pudo crear la reseña.';
        this.cdr.detectChanges();
      }
    });
  }

  eliminarResena(resena: Resena) {
    if (!resena.idResena) return;

    const confirmar = confirm('¿Seguro que querés eliminar esta reseña?');

    if (!confirmar) return;

    this.resenasService.eliminarResena(resena.idResena).subscribe({
      next: () => {
        this.mensaje = 'Reseña eliminada correctamente.';
        this.cargarResenas();
      },
      error: (err) => {
        console.error('Error eliminando reseña:', err);
        this.error = 'No se pudo eliminar la reseña.';
        this.cdr.detectChanges();
      }
    });
  }
}
