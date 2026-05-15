import { Component } from '@angular/core';
import { Router, RouterOutlet } from '@angular/router';
import {  OnInit } from '@angular/core';
import { NavBar } from "../../components/nav-bar/nav-bar";

@Component({
  selector: 'app-home',
  imports: [RouterOutlet, NavBar],
  templateUrl: './home.html',
  styleUrl: './home.scss',
})
export class Home {

  /*auth: boolean = false;

  constructor(private router: Router) {}
  
 
  verificar() {
    if(!this.auth) {
      this.router.navigate(['/auth'])
    }else if(this.auth){
      this.router.navigate(['/home'])
    }
  }
  
  ngOnInit(): void {
    this.verificar();
  }*/

}
