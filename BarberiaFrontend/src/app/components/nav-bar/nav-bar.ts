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
  authService = inject(Auth);
  router = inject(Router);

  modo: string = "Home";
  dropdown: boolean = false;
  usuarioActual: UsuarioSesion | null = null;
  mostrarIniciales = false;

  ngOnInit(): void {
    this.authService.me().subscribe({
      next: (respuesta) => {
        this.usuarioActual = respuesta as UsuarioSesion;
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

    return 'http://localhost/sistema-reservas-barberia/ProyectoBarberiaBackend/public' + this.usuarioActual.foto;
  }

  get inicialesUsuario(): string {
    const nombre = this.usuarioActual?.nombre || 'U';
    const apellido = this.usuarioActual?.apellido || '';

    return `${nombre.charAt(0)}${apellido.charAt(0)}`.toUpperCase();
  }

  usarIniciales(): void {
    this.mostrarIniciales = true;
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
          this.router.navigateByUrl('/auth')
        }
      }
    });

    
  }
}
