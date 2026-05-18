import { Component } from '@angular/core';
import { RouterLink } from "@angular/router";
import { NgClass } from '@angular/common';

@Component({
  selector: 'app-nav-bar',
  imports: [RouterLink,NgClass],
  templateUrl: './nav-bar.html',
  styleUrl: './nav-bar.scss',
})
export class NavBar {
  modo: string = "Home";

  cambiarModo(modo: string){
    this.modo =modo;
  }
}
