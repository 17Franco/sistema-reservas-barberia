import { Component, signal } from '@angular/core';
import { RouterOutlet } from '@angular/router';
import { LoginRegistro } from "./pages/login-registro/login-registro";

@Component({
  selector: 'app-root',
  imports: [RouterOutlet, LoginRegistro],
  templateUrl: './app.html',
  styleUrl: './app.scss'
})
export class App {
  protected readonly title = signal('BarberiaFrontend');
}
