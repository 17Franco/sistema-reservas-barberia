import { Component } from '@angular/core';
import { Login } from '../../components/login/login';
import { Registro } from '../../components/registro/registro';
import { RouterLink } from "@angular/router";
import { NgClass } from '@angular/common';

@Component({
  selector: 'app-login-registro',
  imports: [Login, Registro, RouterLink, NgClass],
  templateUrl: './login-registro.html',
  styleUrl: './login-registro.scss',
})
export class LoginRegistro {
  modo: string = "Login";

  cambiarModo(modo: string){
    this.modo = modo;
  }

  cambiarALogin(){
    this.modo="Login"
  }
}
