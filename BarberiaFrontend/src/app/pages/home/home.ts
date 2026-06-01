import { Component, ChangeDetectorRef, inject } from '@angular/core';
import { RouterOutlet, RouterLink } from '@angular/router';
import { NgFor, NgIf } from '@angular/common';
import { NavBar } from "../../components/nav-bar/nav-bar";
import { Auth } from '../../services/auth';

@Component({
  selector: 'app-home',
  imports: [RouterOutlet, RouterLink, NgFor, NgIf, NavBar],
  templateUrl: './home.html',
  styleUrl: './home.scss',
})
export class Home {
  authService = inject(Auth);
  cd = inject(ChangeDetectorRef);
  serviciosDestacados: any[] = [];
  cargando = true;
  error: string | null = null;

  constructor() {
    this.loadServicios();
  }

  loadServicios(): void {
    console.log('Home.loadServicios iniciada');
    this.cargando = true;
    this.error = null;

    this.authService.getServicios().subscribe({
      next: (res: any) => {
        console.log('Home.getServicios.next', res);
        const servicios = res.servicios || [];
        this.serviciosDestacados = servicios.slice(0, 3);
        this.cargando = false;
        console.log('Home.serviciosDestacados', this.serviciosDestacados, 'cargando=', this.cargando);
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
