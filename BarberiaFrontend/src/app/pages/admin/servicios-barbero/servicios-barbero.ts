import { ChangeDetectorRef, Component, OnInit, inject } from '@angular/core';
import { FormsModule } from '@angular/forms';
import Swal from 'sweetalert2';
import { Auth } from '../../../services/auth';
import { Servicio, ServiciosService } from '../../../services/servicios/servicio';
import { environment } from '../../../../environments/environment';

interface Barbero {
  id?: number;
  nombre: string;
  apellido: string;
  email: string;
  estado: 'ACTIVO' | 'INACTIVO';
  idEspecialidad: number;
  especialidad?: string;
  foto?: string;
}

@Component({
  selector: 'app-servicios-barbero',
  imports: [FormsModule],
  templateUrl: './servicios-barbero.html',
  styleUrl: './servicios-barbero.scss',
})
export class ServiciosBarbero implements OnInit {
  private auth = inject(Auth);
  private serviciosService = inject(ServiciosService);
  private cdr = inject(ChangeDetectorRef);
  public backendPublicUrl = environment.backendPublicUrl;

  barberos: Barbero[] = [];
  servicios: Servicio[] = [];
  filtroBarbero = '';
  idBarberoSeleccionado: number | null = null;
  asignaciones: Record<number, number[]> = {};
  asignacionesOriginales: Record<number, number[]> = {};
  cambiosPendientes = false;
  cargando = false;
  error = '';

  ngOnInit(): void {
    this.cargarDatos();
  }

  cargarDatos(): void {
    this.cargando = true;
    this.error = '';

    this.auth.getBarberos().subscribe({
      next: res => {
        this.barberos = res.empleados || [];
        this.idBarberoSeleccionado = this.barberos[0]?.id || null;
        this.cargarAsignacionesBarberos();
        this.cargando = false;
        this.cdr.detectChanges();
      },
      error: () => {
        this.error = 'No se pudieron cargar los barberos.';
        this.cargando = false;
        this.cdr.detectChanges();
      }
    });

    this.serviciosService.getServicios().subscribe({
      next: res => {
        this.servicios = res.servicios || res;
        this.cdr.detectChanges();
      },
      error: () => {
        this.error = 'No se pudieron cargar los servicios.';
        this.cdr.detectChanges();
      }
    });
  }

  get barberoSeleccionado(): Barbero | null {
    return this.barberos.find(barbero => barbero.id === this.idBarberoSeleccionado) || null;
  }

  get barberosFiltrados(): Barbero[] {
    const filtro = this.filtroBarbero.trim().toLowerCase();
    if (!filtro) return this.barberos;

    return this.barberos.filter(barbero =>
      `${barbero.nombre} ${barbero.apellido} ${barbero.email}`.toLowerCase().includes(filtro)
    );
  }

  seleccionarBarbero(barbero: Barbero): void {
    this.idBarberoSeleccionado = barbero.id || null;
    this.cambiosPendientes = barbero.id ? this.tieneCambiosPendientes(barbero.id) : false;
  }

  estaAsignado(servicio: Servicio): boolean {
    const idServicio = servicio.idServicio;
    const idBarbero = this.idBarberoSeleccionado;

    if (!idServicio || !idBarbero) return false;
    return (this.asignaciones[idBarbero] || []).includes(idServicio);
  }

  alternarServicio(servicio: Servicio): void {
    const idServicio = servicio.idServicio;
    const idBarbero = this.idBarberoSeleccionado;

    if (!idServicio || !idBarbero) return;

    const serviciosAsignados = this.asignaciones[idBarbero] || [];
    this.asignaciones[idBarbero] = serviciosAsignados.includes(idServicio)
      ? serviciosAsignados.filter(id => id !== idServicio)
      : [...serviciosAsignados, idServicio];

    this.cambiosPendientes = this.tieneCambiosPendientes(idBarbero);
  }

  serviciosAsignados(): Servicio[] {
    const idBarbero = this.idBarberoSeleccionado;
    if (!idBarbero) return [];

    const idsAsignados = this.asignaciones[idBarbero] || [];
    return this.servicios.filter(servicio => servicio.idServicio && idsAsignados.includes(servicio.idServicio));
  }

  async guardarCambios(): Promise<void> {
    const idBarbero = this.idBarberoSeleccionado;
    if (!idBarbero) return;

    const servicios = this.asignaciones[idBarbero] || [];

    this.auth.actualizarServiciosBarbero(idBarbero, servicios).subscribe({
      next: async () => {
        this.asignacionesOriginales[idBarbero] = [...servicios];
        this.cambiosPendientes = false;

        await Swal.fire({
          title: 'Servicios actualizados',
          text: 'Los servicios del barbero se guardaron en la base de datos.',
          icon: 'success',
          confirmButtonColor: '#ad6335',
          confirmButtonText: 'Aceptar',
        });
      },
      error: async err => {
        this.error = err.error?.error || 'No se pudieron guardar los servicios.';

        await Swal.fire({
          title: 'Error',
          text: this.error,
          icon: 'error',
          confirmButtonColor: '#ad6335',
          confirmButtonText: 'Aceptar',
        });
      }
    });
  }

  private cargarAsignacionesBarberos(): void {
    for (const barbero of this.barberos) {
      if (!barbero.id) continue;

      this.auth.getServiciosBarbero(barbero.id).subscribe({
        next: res => {
          const ids = (res.servicios || []).map((id: number | string) => Number(id));
          this.asignaciones[barbero.id as number] = ids;
          this.asignacionesOriginales[barbero.id as number] = [...ids];
          this.cdr.detectChanges();
        },
        error: () => {
          this.asignaciones[barbero.id as number] = [];
          this.asignacionesOriginales[barbero.id as number] = [];
          this.cdr.detectChanges();
        }
      });
    }
  }

  private tieneCambiosPendientes(idBarbero: number): boolean {
    const actuales = [...(this.asignaciones[idBarbero] || [])].sort();
    const originales = [...(this.asignacionesOriginales[idBarbero] || [])].sort();

    return actuales.length !== originales.length || actuales.some((id, index) => id !== originales[index]);
  }
}
