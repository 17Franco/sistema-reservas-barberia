import { Component } from '@angular/core';
import { Router } from '@angular/router';
import {  OnInit } from '@angular/core';

@Component({
  selector: 'app-home',
  imports: [],
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
