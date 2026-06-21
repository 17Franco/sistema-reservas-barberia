import { ChangeDetectorRef, Component, OnInit, inject } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { RouterLink } from '@angular/router';
import { Auth } from '../../../services/auth';
import { Servicio, ServiciosService } from '../../../services/servicios/servicio';

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

@Component({
  selector: 'app-gestion-barberos',
  imports: [FormsModule, RouterLink],
  templateUrl: './gestion-barberos.html',
  styleUrl: './gestion-barberos.scss',
})
export class GestionBarberos implements OnInit {
  private auth = inject(Auth);
  private serviciosService = inject(ServiciosService);
  private cdr = inject(ChangeDetectorRef);

  barberos: Barbero[] = [];
  servicios: Servicio[] = [];
  form: Barbero = this.vacio();
  idEditando: number | null = null;
  mensaje = '';
  error = '';

  ngOnInit(): void {
    this.cargar();
    this.serviciosService.getServicios().subscribe(res => {
      this.servicios = res.servicios || res;
      this.form.idEspecialidad ||= this.servicios[0]?.idServicio || 0;
      this.cdr.detectChanges();
    });
  }

  cargar(): void {
    this.auth.getBarberos().subscribe({
      next: res => {
        this.barberos = res.empleados || [];
        this.cdr.detectChanges();
      },
      error: () => this.error = 'No se pudieron cargar los barberos.'
    });
  }

  guardar(): void {
    this.error = '';
    this.mensaje = '';
    if (!this.idEditando && !/^\d{8}$/.test(this.form.ci)) {
      this.error = 'La cédula debe tener exactamente 8 números.';
      return;
    }
    const peticion = this.idEditando
      ? this.auth.actualizarBarbero(this.idEditando, this.form)
      : this.auth.crearBarbero(this.form);

    peticion.subscribe({
      next: () => {
        this.mensaje = this.idEditando ? 'Barbero actualizado.' : 'Barbero agregado.';
        this.cancelar();
        this.cargar();
      },
      error: err => this.error = err.error?.error || 'No se pudo guardar.'
    });
  }

  editar(barbero: Barbero): void {
    this.idEditando = Number(barbero.id);
    this.form = { ...barbero, idEspecialidad: Number(barbero.idEspecialidad), password: '' };
  }

  cambiarEstado(barbero: Barbero): void {
    const estado = barbero.estado === 'ACTIVO' ? 'INACTIVO' : 'ACTIVO';
    if (!confirm(`¿Cambiar estado de ${barbero.nombre} a ${estado}?`)) return;
    this.auth.cambiarEstadoBarbero(barbero.ci, estado).subscribe(() => this.cargar());
  }

  cancelar(): void {
    this.idEditando = null;
    this.form = this.vacio();
    this.form.idEspecialidad = this.servicios[0]?.idServicio || 0;
  }

  private vacio(): Barbero {
    return {
      ci: '', nombre: '', apellido: '', fechaNac: '', password: '',
      email: '', celular: '', estado: 'ACTIVO', idEspecialidad: 0
    };
  }
}
