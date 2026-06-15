import { Component, OnInit, inject, ChangeDetectorRef } from '@angular/core';
import { Auth } from '../../services/auth';
import { firstValueFrom } from 'rxjs';
import { FormsModule } from '@angular/forms';

interface UsuarioSesion {
  logueado: boolean;
  usuario_id?: number;
  nombre?: string;
  apellido?: string;
  foto?: string;
  tipo?: string;
  email?:string;
  celular?:string;
  fechaCreacion?: string;
  direccion?:string;
}

interface ReservaInterface {
  idReserva: number;
  idServicio: number;
  idEmpleado: number;

  estado: string;
  fecha: string;
  horaInicio: string;

  nombreServicio: string;
  descripcion: string;
  duracion: number;
  precio: number;

  nombreEmpleado: string;
  apellidoEmpleado: string;
  fotoEmpleado?: string | null;
}

@Component({
  selector: 'app-pagina-perfil',
  imports: [FormsModule],
  templateUrl: './pagina-perfil.html',
  styleUrl: './pagina-perfil.scss',
})
export class PaginaPerfil implements OnInit {
  private readonly backendPublicUrl = 'http://localhost/sistema-reservas-barberia/ProyectoBarberiaBackend/public';

  //para detectar los cambios porque tiene delay
  private cd = inject(ChangeDetectorRef);

  //servicio para pegarle al backend
  private auth = inject(Auth);

  //Variables, tienen que ses ppublic para poder usarlas en el html
  public editandoPerfil = false;
  //se carga con la funcion cargarPerfil
  public usuarioActual: UsuarioSesion | null = null;
  public cargando = true;
  public error = '';
  public mostrarIniciales = false;
  //Aqui guardo las reservas asociadas al cliente
  public reservasAsociadas: ReservaInterface[] = [];

  //para poder editar el usuario
  formPerfil = {
    nombre: '',
    apellido: '',
    celular: '',
    direccion:'',
  };

  activarEdicion() {
    this.editandoPerfil = true;

    this.formPerfil = {
      nombre: this.usuarioActual?.nombre || '',
      apellido: this.usuarioActual?.apellido || '',
      celular: this.usuarioActual?.celular || '',
      direccion: this.usuarioActual?.direccion || '',
    };
  }

  cancelarEdicion() {
    this.editandoPerfil = false;
  }

  async guardarEdicion(): Promise<void> {
    if (!this.usuarioActual) {
      return;
    }

    try {
      const respuesta = await firstValueFrom(this.auth.editarPerfilUsuario(this.formPerfil));
      //los 3 . son para copiar todas las propiedades del usuario aunque no las use todas (sino no se guardarian to)
      if (respuesta.success) {
        this.usuarioActual = {
          ...this.usuarioActual,
          nombre: this.formPerfil.nombre,
          apellido: this.formPerfil.apellido,
          celular: this.formPerfil.celular,
          direccion: this.formPerfil.direccion,
        };

        this.editandoPerfil = false;
      }
    } catch (error) {
      console.error('Error editando perfil', error);
      this.error = 'No se pudo editar el perfil.';
    }
  }


  //uso esta funcion para decirle que llame a mi funcion cargarPerfil en cuanto inicie la paginaPeril
  ngOnInit(): void {
    this.cargarPerfil();
  }
  //duncion para cargar el usuario
  async cargarPerfil(): Promise<void> {
  try {
    const respuesta = await firstValueFrom(this.auth.me());
    console.log('Perfil:', respuesta);

    this.usuarioActual = respuesta as UsuarioSesion;

    const idCliente = this.usuarioActual.usuario_id;
    
    if (idCliente) {
      const respuestaReservas = await firstValueFrom(
        this.auth.getReservasClienteAsociado(idCliente)
      );

      console.log('Reservas:', respuestaReservas);
      //cargo las reservas en la variable
      this.reservasAsociadas = respuestaReservas.mensaje || [];
    }

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

    return this.urlImagenBackend(this.usuarioActual.foto);
  }

  fotoEmpleadoUrl(reserva: ReservaInterface): string | null {
    if (!reserva.fotoEmpleado) {
      return null;
    }

    return this.urlImagenBackend(reserva.fotoEmpleado);
  }

  nombreCompletoEmpleado(reserva: ReservaInterface): string {
    return `${reserva.nombreEmpleado || ''} ${reserva.apellidoEmpleado || ''}`.trim() || 'Barbero sin dato';
  }

  private urlImagenBackend(ruta: string): string {
    if (ruta.startsWith('http://') || ruta.startsWith('https://')) {
      return ruta;
    }

    return `${this.backendPublicUrl}${ruta}`;
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

  fechaReserva(reserva: ReservaInterface): string {
    return `${this.diaMes(reserva.fecha)}/${reserva.fecha.slice(0, 4)} · ${reserva.horaInicio}`;
  }

  fechaUsuario(fecha?: string): string {
    if (!fecha) {
      return 'No registrada';
    }

    return fecha.split(' ')[0].split('-').reverse().join('/');
  }

  //Funcion para segun el estado mostrar una alerta diferente
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
