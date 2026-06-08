import { Component, ChangeDetectorRef, inject } from '@angular/core';
import { RouterOutlet, RouterLink, Router } from '@angular/router';
import { NgFor, NgIf } from '@angular/common';
import { NavBar } from "../../components/nav-bar/nav-bar";
import { Auth } from '../../services/auth';

@Component({
  selector: 'app-pantalla-principal',
  imports: [RouterOutlet, RouterLink, NgFor, NgIf, NavBar],
  templateUrl: './pantalla-principal.html',
  styleUrl: './pantalla-principal.scss',
})
export class PantallaPrincipal {
  authService = inject(Auth);
  router = inject(Router);
  cd = inject(ChangeDetectorRef);
  serviciosDestacados: any[] = [];
  cargando = true;
  error: string | null = null;

  constructor() {
    this.loadServicios();
  }

  loadServicios(): void {
    console.log('PantallaPrincipal.loadServicios iniciada');
    this.cargando = true;
    this.error = null;

    this.authService.getServicios().subscribe({
      next: (res: any) => {
        console.log('PantallaPrincipal.getServicios.next', res);
        const servicios = res.servicios || [];
        this.serviciosDestacados = servicios.slice(0, 3);
        this.cargando = false;
        console.log('PantallaPrincipal.serviciosDestacados', this.serviciosDestacados, 'cargando=', this.cargando);
        this.cd.detectChanges();
      },
      error: (err) => {
        console.error('Servicio /servicios error:', err);
        this.error = 'No se pudieron cargar los servicios destacados. Verifica el backend o la conexión.';
        this.cargando = false;
        this.cd.detectChanges();
      },
    });
  }
}
