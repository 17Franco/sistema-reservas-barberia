import { Component, inject } from '@angular/core';
import { FormGroup, FormControl, Validators, ReactiveFormsModule } from '@angular/forms';
import { Router } from '@angular/router';
import { Auth } from '../../../services/auth';

@Component({
  selector: 'app-crear-servicio',
  imports: [ReactiveFormsModule],
  templateUrl: './crear-servicio.html',
  styleUrl: './crear-servicio.scss'
})
export class CrearServicio {

  private authService = inject(Auth);
  private router = inject(Router);

  mensaje: string = '';
  error: string = '';

  form = new FormGroup({
    nombre: new FormControl('', [Validators.required]),
    descripcion: new FormControl('', [Validators.required]),
    precio: new FormControl('', [Validators.required, Validators.min(1)]),
    img: new FormControl('', [Validators.required])
  });

  crear() {
    this.mensaje = '';
    this.error = '';

    if (this.form.invalid) {
      this.error = 'Completa todos los campos correctamente';
      return;
    }

    const data = this.form.value;

    //backend aún no tiene POST, pero dejamos listo
    this.authService.crearServicio(data).subscribe({
      next: () => {
        this.mensaje = 'Servicio creado correctamente';
        this.form.reset();

        setTimeout(() => {
          this.router.navigateByUrl('/servicios');
        }, 1000);
      },
      error: () => {
        this.error = 'Error al crear servicio';
      }
    });
  }
}