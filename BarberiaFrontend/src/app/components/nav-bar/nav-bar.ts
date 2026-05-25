import { Component, inject } from '@angular/core';
import { RouterLink } from "@angular/router";
import { NgClass } from '@angular/common';
import { Auth } from '../../services/auth';
import { Router } from '@angular/router';
@Component({
  selector: 'app-nav-bar',
  imports: [RouterLink,NgClass],
  templateUrl: './nav-bar.html',
  styleUrl: './nav-bar.scss',
})
export class NavBar {
  authService = inject(Auth);
  router = inject(Router);

  modo: string = "Home";
  dropdown: boolean = false;

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
