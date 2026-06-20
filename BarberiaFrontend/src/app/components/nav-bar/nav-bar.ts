import { Component, OnInit, inject } from '@angular/core';
import { RouterLink } from "@angular/router";
import { NgClass } from '@angular/common';
import { Auth } from '../../services/auth';
import { Router } from '@angular/router';

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
  private readonly backendPublicUrl = 'http://localhost/sistema-reservas-barberia/ProyectoBarberiaBackend/public';

  authService = inject(Auth);
  router = inject(Router);

  modo: string = "Home";
  dropdown: boolean = false;
  usuarioActual: UsuarioSesion | null = null;
  mostrarIniciales = false;
  admin: boolean = false;
  ngOnInit(): void {
    this.admin = this.isAdmin();
    this.authService.me().subscribe({
      next: (respuesta) => {
        this.usuarioActual = respuesta as UsuarioSesion;
        this.mostrarIniciales = false;
      },
      error: () => {
        this.usuarioActual = null;
      },
    });
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
    setTimeout(() => {
      this.mostrarIniciales = true;
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

  isAdmin(){
    console.log(this.authService.usuario.tipo);
    if(this.authService.usuario.tipo=="ADMIN"){
      return true;
    } 
    return false;
  }
}
