import { ChangeDetectorRef, Component, inject } from '@angular/core';
import { RouterLink } from '@angular/router';
import { NgFor, NgIf } from '@angular/common';
import { NavBar } from '../../components/nav-bar/nav-bar';
import { Auth } from '../../services/auth';
import { ServiciosService } from '../../services/servicios/servicio';

@Component({
  selector: 'app-servicios',
  imports: [RouterLink, NgFor, NgIf, NavBar],
  templateUrl: './servicios.html',
  styleUrl: './servicios.scss',
})
export class Servicios {
  authService = inject(Auth);
  ServiciosService = inject(ServiciosService);
  cd = inject(ChangeDetectorRef);
  servicios: any[] = [];
  cargando = true;
  error: string | null = null;

  constructor() {
    this.loadServicios();
  }

  loadServicios(): void {
    this.cargando = true;
    this.error = null;

    this.ServiciosService.getServicios().subscribe({
      next: (res: any) => {
        this.servicios = res.servicios || [];
        this.cargando = false;
        this.cd.detectChanges();
      },
      error: (err) => {
        console.error(err);
        this.error = 'No se pudieron cargar los servicios.';
        this.cargando = false;
        this.cd.detectChanges();
      }
    });
  }
}
