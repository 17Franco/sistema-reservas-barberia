import { Component, OnInit, inject, ChangeDetectorRef } from '@angular/core';
import { Auth } from '../../services/auth';
import { firstValueFrom } from 'rxjs';

interface UsuarioSesion {
  logueado: boolean;
  usuario_id?: number;
  nombre?: string;
  apellido?: string;
  foto?: string;
  tipo?: string;
}

interface ReservaPerfil {
  idReserva: number;
  fecha: string;
  horaInicio: string;
  servicio?: string;
  barbero?: string;
  estado?: string;
}

@Component({
  selector: 'app-pagina-perfil',
  imports: [],
  templateUrl: './pagina-perfil.html',
  styleUrl: './pagina-perfil.scss',
})
export class PaginaPerfil implements OnInit {
  //para detectar los cambios porque tiene delay
  private cd = inject(ChangeDetectorRef);

  //servicio para pegarle al backend
  private auth = inject(Auth);

  public usuarioActual: UsuarioSesion | null = null;
  public cargando = true;
  public error = '';
  public mostrarIniciales = false;
  public reservas: ReservaPerfil[] = [];

  //uso esta funcion piruja para decirle que llame a mi funcion cargarPerfil en cuanto inicie la paginaPeril

  ngOnInit(): void {
    this.cargarPerfil();
  }

  async cargarPerfil(): Promise<void> {
    try {
      const respuesta = await firstValueFrom(this.auth.me());
      console.log(respuesta);
      this.usuarioActual = respuesta as UsuarioSesion;
    } catch (error) {
      console.error('Error cargando perfil', error);
      this.error = 'No se pudo cargar el perfil.';
    } finally {
      this.cargando = false;
      this.cd.detectChanges();
    }
  }

  get fotoPerfilUrl(): string | null {
    if (!this.usuarioActual?.foto || this.mostrarIniciales) {
      return null;
    }

    return 'http://localhost/sistema-reservas-barberia/ProyectoBarberiaBackend/public' + this.usuarioActual.foto;
  }

  usarIniciales(): void {
    //es porque angular hace todo al mismo tiempo y sino se rompe
  setTimeout(() => {
    this.mostrarIniciales = true;
  },0);
}

  inicialesUsuario(): string {
    const nombre = this.usuarioActual?.nombre || 'U';
    const apellido = this.usuarioActual?.apellido || '';

    return `${nombre.charAt(0)}${apellido.charAt(0)}`.toUpperCase();
  }

  diaMes(fecha: string): string {
    const partes = fecha.split('-');

    if (partes.length !== 3) {
      return fecha;
    }

    return `${partes[2]}/${partes[1]}`;
  }

  fechaReserva(reserva: ReservaPerfil): string {
    return `${this.diaMes(reserva.fecha)}/${reserva.fecha.slice(0, 4)} · ${reserva.horaInicio}`;
  }

  claseEstado(estado?: string): string {
    const estadoNormalizado = estado?.toLowerCase() || '';

    if (estadoNormalizado === 'confirmada') {
      return 'bg-success';
    }

    if (estadoNormalizado === 'pendiente') {
      return 'bg-warning text-dark';
    }

    if (estadoNormalizado === 'cancelada') {
      return 'bg-danger';
    }

    return 'bg-secondary';
  }
}
