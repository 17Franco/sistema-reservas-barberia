import { CommonModule } from '@angular/common';
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

@Component({
  selector: 'app-pagina-perfil',
  imports: [CommonModule],
  templateUrl: './pagina-perfil.html',
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
    this.mostrarIniciales = true;
  }
}
