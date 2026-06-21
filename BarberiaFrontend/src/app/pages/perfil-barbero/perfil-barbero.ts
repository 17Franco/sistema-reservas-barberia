import { ChangeDetectorRef, Component, OnInit, inject } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { firstValueFrom } from 'rxjs';
import { Auth } from '../../services/auth';
import Swal from 'sweetalert2';

interface UsuarioSesion {
  logueado: boolean;
  usuario_id?: number;
  nombre?: string;
  apellido?: string;
  foto?: string;
  tipo?: string;
  email?: string;
  celular?: string;
  fechaCreacion?: string;
  direccion?: string;
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
  nombreCliente : string;
  apellidoCliente: string;
  fotoCliente?: string | null;
}

interface FormularioPerfil {
  nombre: string;
  apellido: string;
  celular: string;
  direccion: string;
}

@Component({
  selector: 'app-perfil-barbero',
  imports: [FormsModule],
  templateUrl: './perfil-barbero.html',
  styleUrl: './perfil-barbero.scss',
})
export class PerfilBarbero implements OnInit{
  // Dependencias y configuración
  private readonly backendPublicUrl =
    'http://localhost/sistema-reservas-barberia/ProyectoBarberiaBackend/public';
  private readonly cd = inject(ChangeDetectorRef);
  private readonly auth = inject(Auth);

  // Estado de la pantalla
  public usuarioActual: UsuarioSesion | null = null;
  public reservasAsociadas: ReservaInterface[] = [];
  public historialReservas: ReservaInterface[] = [];
  public editandoPerfil = false;
  public cargando = true;
  public mostrarIniciales = false;
  public error = '';
  public paginaReservas = 1;  //pagina en la que inicio de reservas
  public paginaHistorial =1;
  public reservasPorPagina = 3;       //variables para definir la cantidad de reservas por etiqueta
  public reservasHistorialPorPagina = 3;
  public cantidadPendientes = 0;
  public cantidadConfirmadas = 0;
  public cantidadCanceladas = 0;
  public cantidadCompletadas = 0;
  public archivoImagen: File | null = null;
  public vistaPreviaImagen: string | null = null;
  public errorImagen = '';
  public reservaCancelandoId: number | null = null;
  public mostrarOpcionesCancelar = false;
  public reservaConfirmandoId: number | null = null;
  public mostrarOpcionesConfirmar = false;


  // Datos del formulario de edición
  public formPerfil: FormularioPerfil = {
    nombre: '',
    apellido: '',
    celular: '',
    direccion: '',
  };

  ngOnInit(): void {
    this.cargarPerfil();
  }

  async cargarPerfil(): Promise<void> {
    try {
      const respuesta = await firstValueFrom(this.auth.me());
      this.usuarioActual = respuesta as UsuarioSesion;

      const idBarbero = this.usuarioActual.usuario_id;

      if (idBarbero) {
        const respuestaReservas = await firstValueFrom(
          this.auth.getReservasBarberoAsociado(idBarbero),
        );

        const reservas = respuestaReservas.mensaje || [];
        //Relleno con un valor las variables de cantidades de reservas
        this.cantidadPendientes = reservas.filter(
          (reserva: ReservaInterface) => reserva.estado === 'PENDIENTE',
        ).length;

        this.cantidadConfirmadas = reservas.filter(
          (reserva: ReservaInterface) => reserva.estado === 'CONFIRMADA',
        ).length;

        this.cantidadCanceladas = reservas.filter(
          (reserva: ReservaInterface) => reserva.estado === 'CANCELADA',
        ).length;

        this.cantidadCompletadas = reservas.filter(
          (reserva: ReservaInterface) => reserva.estado === 'COMPLETADA',
        ).length;

        this.reservasAsociadas = reservas.filter(
          (reserva: ReservaInterface) => reserva.estado !== 'COMPLETADA',
        );

        this.historialReservas = reservas.filter(
          (reserva: ReservaInterface) => reserva.estado === 'COMPLETADA',
        );
      }
    } catch (error) {
      console.error('Error cargando perfil', error);
      this.error = 'No se pudo cargar el perfil.';
    } finally {
      this.cargando = false;
      this.cd.detectChanges();
    }
  }

  activarEdicion(): void {
    this.editandoPerfil = true;
    this.formPerfil = {
      nombre: this.usuarioActual?.nombre || '',
      apellido: this.usuarioActual?.apellido || '',
      celular: this.usuarioActual?.celular || '',
      direccion: this.usuarioActual?.direccion || '',
    };
    this.archivoImagen = null;
    this.errorImagen = '';
    this.vistaPreviaImagen = this.fotoPerfilUrl;
  }

  cancelarEdicion(): void {
    this.editandoPerfil = false;
    this.limpiarSeleccionImagen();
  }

  async guardarEdicion(): Promise<void> {
    if (!this.usuarioActual) {
      return;
    }

    try {
      const datos = new FormData();
      datos.append('nombre', this.formPerfil.nombre);
      datos.append('apellido', this.formPerfil.apellido);
      datos.append('celular', this.formPerfil.celular);
      datos.append('direccion', this.formPerfil.direccion);

      if (this.archivoImagen) {
        datos.append('foto', this.archivoImagen);
      }

      const respuesta = await firstValueFrom(
        this.auth.editarPerfilUsuario(datos),
      );

      if (respuesta.success) {
        this.usuarioActual = {
          ...this.usuarioActual,
          ...this.formPerfil,
          foto: respuesta.usuario.foto,
        };

        this.editandoPerfil = false;
        this.mostrarIniciales = false;

        // Auth comparte estos cambios con el navbar.
        this.auth.actualizarUsuario({
          ...this.formPerfil,
          foto: respuesta.usuario.foto,
        });
        this.limpiarSeleccionImagen();
      }
    } catch (error) {
      console.error('Error editando perfil', error);
      this.error = 'No se pudo editar el perfil.';
    }
  }

  get fotoPerfilUrl(): string | null {
    if (!this.usuarioActual?.foto || this.mostrarIniciales) {
      return null;
    }

    return this.urlImagenBackend(this.usuarioActual.foto);
  }

  fotoClienteUrl(reserva: ReservaInterface): string | null {
    if (!reserva.fotoCliente) {
      return null;
    }

    return this.urlImagenBackend(reserva.fotoCliente);
  }

  usarIniciales(): void {
    setTimeout(() => {
      this.mostrarIniciales = true;
    }, 0);
  }

  seleccionarImagen(evento: Event): void {
    const input = evento.target as HTMLInputElement;
    const archivo = input.files?.[0];

    if (archivo) {
      this.procesarImagen(archivo);
    }
  }

  arrastrarSobreImagen(evento: DragEvent): void {
    evento.preventDefault();
  }

  soltarImagen(evento: DragEvent): void {
    evento.preventDefault();
    const archivo = evento.dataTransfer?.files?.[0];

    if (archivo) {
      this.procesarImagen(archivo);
    }
  }

  private procesarImagen(archivo: File): void {
    const formatosPermitidos = ['image/jpeg', 'image/png', 'image/webp'];

    if (!formatosPermitidos.includes(archivo.type)) {
      this.errorImagen = 'Seleccioná una imagen JPG, PNG o WEBP.';
      return;
    }

    if (archivo.size > 5 * 1024 * 1024) {
      this.errorImagen = 'La imagen no puede superar los 5 MB.';
      return;
    }

    this.liberarVistaPreviaLocal();
    this.archivoImagen = archivo;
    this.vistaPreviaImagen = URL.createObjectURL(archivo);
    this.errorImagen = '';
  }

  private limpiarSeleccionImagen(): void {
    this.liberarVistaPreviaLocal();
    this.archivoImagen = null;
    this.vistaPreviaImagen = null;
    this.errorImagen = '';
  }

  private liberarVistaPreviaLocal(): void {
    if (this.vistaPreviaImagen?.startsWith('blob:')) {
      URL.revokeObjectURL(this.vistaPreviaImagen);
    }
  }

  inicialesUsuario(): string {
    const nombre = this.usuarioActual?.nombre || 'U';
    const apellido = this.usuarioActual?.apellido || '';

    return `${nombre.charAt(0)}${apellido.charAt(0)}`.toUpperCase();
  }

  nombreCompletoCliente(reserva: ReservaInterface): string {
    return (
      `${reserva.nombreCliente || ''} ${reserva.apellidoCliente || ''}`.trim() ||
      'Barbero sin dato'
    );
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

  //Esta función es para asignarle un estilo dependiendo el estado de la reserva
  claseEstado(estado?: string, reserva?: ReservaInterface): string {
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

    if (estadoNormalizado === 'completada') {
      return 'bg-primary';
    }

    return 'bg-secondary';
  }

  private urlImagenBackend(ruta: string): string {
    if (ruta.startsWith('http://') || ruta.startsWith('https://')) {
      return ruta;
    }

    return `${this.backendPublicUrl}${ruta}`;
  }

  private diaMes(fecha: string): string {
    const partes = fecha.split('-');

    if (partes.length !== 3) {
      return fecha;
    }

    return `${partes[2]}/${partes[1]}`;
  }

//Funcionalidad de movimiento entre reservas
 get reservasVisibles(): ReservaInterface[] {
    const inicio = (this.paginaReservas - 1) * this.reservasPorPagina;

    return this.reservasAsociadas.slice(
      inicio,
      inicio + this.reservasPorPagina,
    );
  }

  get historialVisible(): ReservaInterface[] {
    const inicio =
      (this.paginaHistorial - 1) * this.reservasHistorialPorPagina;

    return this.historialReservas.slice(
      inicio,
      inicio + this.reservasHistorialPorPagina,
    );
  }

  get totalPaginasReservas(): number {
    return Math.max(
      1,
      Math.ceil(this.reservasAsociadas.length / this.reservasPorPagina),
    );
  }

  get totalPaginasHistorial(): number {
    return Math.max(
      1,
      Math.ceil(
        this.historialReservas.length / this.reservasHistorialPorPagina,
      ),
    );
  }

  async cancelarReserva(reserva: ReservaInterface): Promise<void> {
  if (reserva.estado !== 'PENDIENTE') {
    return;
  }

  const resultado = await Swal.fire({
    title: '¿Seguro que quieres cancelar la reserva?',
    text: 'Esta acción no se puede revertir.',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#dc3545',
    cancelButtonColor: '#6c757d',
    confirmButtonText: 'Sí, cancelar',
    cancelButtonText: 'Volver',
  });

  if (!resultado.isConfirmed) {
    return;
  }

  this.reservaCancelandoId = reserva.idReserva;

  try {
    await firstValueFrom(
      this.auth.cancelarReserva(reserva.idReserva)
    );

    this.reservasAsociadas = this.reservasAsociadas.map(
      reservaActual =>
        reservaActual.idReserva === reserva.idReserva
          ? { ...reservaActual, estado: 'CANCELADA' }
          : reservaActual
    );

    this.cantidadPendientes = Math.max(
      0,
      this.cantidadPendientes - 1
    );

    this.cantidadCanceladas++;

    this.cd.detectChanges();

    await Swal.fire({
      title: 'Reserva cancelada',
      text: 'La reserva fue cancelada correctamente.',
      icon: 'success',
    });

  } catch (error) {
    console.error('Error cancelando reserva', error);

    await Swal.fire({
      title: 'Error',
      text: 'No se pudo cancelar la reserva.',
      icon: 'error',
    });

  } finally {
    this.reservaCancelandoId = null;
    this.cd.detectChanges();
  }
}



async confirmarReserva(reserva: ReservaInterface): Promise<void> {
  if (reserva.estado !== 'PENDIENTE') {
    return;
  }

  const resultado = await Swal.fire({
    title: '¿Seguro que quieres confirmar la reserva?',
    text: 'La reserva pasará al estado confirmada.',
    icon: 'question',
    showCancelButton: true,
    confirmButtonColor: '#198754',
    cancelButtonColor: '#6c757d',
    confirmButtonText: 'Sí, confirmar',
    cancelButtonText: 'Volver',
  });

  if (!resultado.isConfirmed) {
    return;
  }

  this.reservaConfirmandoId = reserva.idReserva;

  try {
    await firstValueFrom(
      this.auth.confirmarReserva(reserva.idReserva)
    );

    this.reservasAsociadas = this.reservasAsociadas.map(
      reservaActual =>
        reservaActual.idReserva === reserva.idReserva
          ? { ...reservaActual, estado: 'CONFIRMADA' }
          : reservaActual
    );

    this.cantidadPendientes = Math.max(
      0,
      this.cantidadPendientes - 1
    );

    this.cantidadConfirmadas++;

    this.cd.detectChanges();

    await Swal.fire({
      title: 'Reserva confirmada',
      text: 'La reserva fue confirmada correctamente.',
      icon: 'success',
    });

  } catch (error) {
    console.error('Error confirmando reserva', error);

    await Swal.fire({
      title: 'Error',
      text: 'No se pudo confirmar la reserva.',
      icon: 'error',
    });

  } finally {
    this.reservaConfirmandoId = null;
    this.cd.detectChanges();
  }
}

}
