import { ChangeDetectorRef, Component, OnInit, inject } from '@angular/core';
import { RouterLink } from "@angular/router";
import { NgClass } from '@angular/common';
import { Auth } from '../../services/auth';
import { Router } from '@angular/router';
import { environment } from '../../../environments/environment';

interface UsuarioSesion {
  logueado: boolean;
  usuario_id?: number;
  nombre?: string;
  apellido?: string;
  foto?: string;
  tipo?: string;
}

@Component({
  selector: 'app-nav-bar',
  imports: [RouterLink,NgClass],
  templateUrl: './nav-bar.html',
  styleUrl: './nav-bar.scss',
})
export class NavBar implements OnInit {
  private readonly backendPublicUrl = environment.backendPublicUrl;

  authService = inject(Auth);
  router = inject(Router);
  private cd = inject(ChangeDetectorRef);

  modo: string = "Home";
  dropdown: boolean = false;
  mostrarIniciales = false;
  
  ngOnInit(): void {
     
    this.authService.me().subscribe({
      next: (respuesta) => {
        const usuario = respuesta as UsuarioSesion;
        this.authService.usuario = usuario.logueado ? usuario : null;
        this.mostrarIniciales = false;
        this.cd.markForCheck();
      },
      error: () => {
        this.authService.usuario = null;
        this.cd.markForCheck();
      },
    });
  }

  get usuarioActual(): UsuarioSesion | null {
    return this.authService.usuario as UsuarioSesion | null;
  }

  get admin(): boolean {
    return this.usuarioActual?.tipo === 'ADMIN';
  }

   get empleado(): boolean {
    return this.usuarioActual?.tipo === 'EMPLEADO';
  }

  get fotoPerfilUrl(): string | null {
    if (!this.usuarioActual?.foto || this.mostrarIniciales) {
      return null;
    }

    return this.urlImagenBackend(this.usuarioActual.foto);
  }

  private urlImagenBackend(ruta: string): string {
    if (ruta.startsWith('http://') || ruta.startsWith('https://')) {
      return ruta;
    }

    return `${this.backendPublicUrl}${ruta.startsWith('/') ? ruta : `/${ruta}`}`;
  }

  get inicialesUsuario(): string {
    const nombre = this.usuarioActual?.nombre || 'U';
    const apellido = this.usuarioActual?.apellido || '';

    return `${nombre.charAt(0)}${apellido.charAt(0)}`.toUpperCase();
  }

  usarIniciales(): void {
    // La carga de la imagen ocurre fuera del flujo de datos del componente.
    // Avisamos a Angular para que muestre las iniciales sin esperar otro clic.
    setTimeout(() => {
      this.mostrarIniciales = true;
      this.cd.markForCheck();
    }, 0);
  }

  viewdropdawn(){
    this.dropdown = ! this.dropdown ;
  }
  cambiarModo(modo: string){
    this.modo =modo;
  }

  logOut(){
    this.authService.logOut().subscribe({
      next:(res:any)=>{
        if(res.success){
          console.log(res);
          this.authService.usuario = null;
          this.router.navigateByUrl('/auth')
          
        }
      }
    });
  }

  isAdmin(): boolean {
    return this.admin;
  }
}
