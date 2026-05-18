import { Component } from '@angular/core';
import { NgClass } from '@angular/common';

@Component({
  selector: 'app-registro',
  imports: [NgClass],
  templateUrl: './registro.html',
  styleUrl: './registro.scss',
})
export class Registro {

  step: number = 1;

  sigStep(){
    if(this.step<3){
      this.step=this.step+1;
    }
  }

  antStep(){
    if(this.step>1){
      this.step=this.step-1;
    }
  }

}
