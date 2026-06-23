import { ChangeDetectorRef, Component, OnInit, inject } from '@angular/core';
import { FormsModule, NgForm } from '@angular/forms';
import { RouterLink } from '@angular/router';
import { Auth } from '../../../services/auth';
import { Servicio, ServiciosService } from '../../../services/servicios/servicio';
import { firstValueFrom } from 'rxjs';
import Swal from 'sweetalert2';

interface Barbero {
  id?: number;
  ci: string;
  nombre: string;
  apellido: string;
  fechaNac: string;
  password: string;
  email: string;
  celular: string;
  horaIni: string;
  horaFin: string;
  horaDescansoIni: string;
  horaDescansoFin: string;
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

  public barberos: Barbero[] = [];
  public servicios: Servicio[] = [];
  public form: Barbero = this.vacio();
  public idEditando: number | null = null;
  public mensaje = '';
  public error = '';
  public fechaMaximaNacimiento = this.calcularFechaMaximaNacimiento();

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

  guardar(formulario: NgForm): void {
    this.error = '';
    this.mensaje = '';

    if (formulario.invalid || (!this.idEditando && this.fechaNacimientoInvalida())) {
      formulario.form.markAllAsTouched();
      this.error = 'Completá los campos marcados para registrar el barbero.';
      return;
    }

    if (!this.idEditando && !/^\d{8}$/.test(this.form.ci)) {
      this.error = 'La cédula debe tener exactamente 8 números.';
      return;
    }
    if (!this.idEditando && this.form.fechaNac > this.fechaMaximaNacimiento) {
      this.error = 'El barbero debe tener al menos 18 años.';
      return;
    }
    const peticion = this.idEditando
      ? this.auth.actualizarBarbero(this.idEditando, this.form)
      : this.auth.crearBarbero(this.form);

    peticion.subscribe({
      next: async () => {
        this.mensaje = this.idEditando ? 'Barbero actualizado.' : 'Barbero agregado.';
        if (!this.idEditando) {
          await Swal.fire({
            title: 'Barbero agregado',
            text: `${this.form.nombre} ${this.form.apellido} ya forma parte del equipo.`,
            icon: 'success',
            confirmButtonColor: '#ad6335',
            confirmButtonText: 'Aceptar',
          });
        }
        this.cancelar(formulario);
        this.cargar();
      },
      error: err => {
        const mensaje = err.error?.error || '';
        this.error = mensaje.includes('cédula') || mensaje.includes('email')
          ? 'La cédula o el email ya están registrados.'
          : mensaje || 'No se pudo guardar.';
      }
    });
  }

  editar(barbero: Barbero): void {
    this.idEditando = Number(barbero.id);
    this.form = { ...barbero, idEspecialidad: Number(barbero.idEspecialidad), password: '' };
  }

  async cambiarEstado(barbero: Barbero): Promise<void> {
    const estado = barbero.estado === 'ACTIVO' ? 'INACTIVO' : 'ACTIVO';

    const resultado = await Swal.fire({
      title: `¿${estado === 'INACTIVO' ? 'Dar de baja' : 'Reactivar'} a ${barbero.nombre}?`,
      text: `El barbero pasará al estado ${estado}.`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: estado === 'INACTIVO' ? '#dc3545' : '#198754',
      cancelButtonColor: '#6c757d',
      confirmButtonText: estado === 'INACTIVO' ? 'Sí, dar de baja' : 'Sí, reactivar',
      cancelButtonText: 'Cancelar',
    });

    if (!resultado.isConfirmed) return;

    try {
      await firstValueFrom(this.auth.cambiarEstadoBarbero(barbero.ci, estado));
      this.cargar();

      await Swal.fire({
        title: 'Estado actualizado',
        text: `${barbero.nombre} ahora está ${estado.toLowerCase()}.`,
        icon: 'success',
      });
    } catch (error: any) {
      await Swal.fire({
        title: 'Error',
        text: error.error?.error || 'No se pudo cambiar el estado del barbero.',
        icon: 'error',
      });
    }
  }

  cancelar(formulario?: NgForm): void {
    this.idEditando = null;
    const formVacio = this.vacio();
    formVacio.idEspecialidad = this.servicios[0]?.idServicio || 0;
    this.form = formVacio;
    formulario?.resetForm(formVacio);
  }

  private vacio(): Barbero {
    return {
      ci: '',
      nombre: '',
      apellido: '',
      fechaNac: '',
      password: '',
      email: '',
      celular: '',
      estado: 'ACTIVO',
      idEspecialidad: 0,
      horaIni: '09:00',
      horaFin: '17:00',
      horaDescansoIni: '00:00',
      horaDescansoFin: '00:00'
    };
  }

  private calcularFechaMaximaNacimiento(): string {
    const fecha = new Date();
    fecha.setFullYear(fecha.getFullYear() - 18);
    return fecha.toISOString().split('T')[0];
  }

  fechaNacimientoInvalida(): boolean {
    if (!this.form.fechaNac) {
      return true;
    }

    const fechaIngresada = new Date(`${this.form.fechaNac}T00:00:00`);
    const hoy = new Date();
    const fechaMayorEdad = new Date(`${this.fechaMaximaNacimiento}T00:00:00`);

    hoy.setHours(0, 0, 0, 0);

    return fechaIngresada > hoy || fechaIngresada > fechaMayorEdad;
  }

  mensajeErrorFecha(): string {
  if (!this.form.fechaNac) {
    return 'Ingresá la fecha de nacimiento.';
  }
  //Le pido la fecha al formulario
  const fechaIngresada = new Date(this.form.fechaNac);
  const hoy = new Date();   //saco la fecha de hoy
  const fechaMayorEdad = new Date(this.fechaMaximaNacimiento);

  if (fechaIngresada > hoy) {
    return 'O viajaste en el tiempo o estás metiendo cualquier dato che.';
  }

  if (fechaIngresada > fechaMayorEdad) {
    return 'El barbero debe tener al menos 18 años, acá no explotamos menores.';
  }

  return 'Ingresá una fecha válida dale.';
}

}
