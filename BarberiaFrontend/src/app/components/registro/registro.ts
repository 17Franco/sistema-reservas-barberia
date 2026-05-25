import { Component, inject } from '@angular/core';
import { NgClass } from '@angular/common';
import {FormGroup,FormControl, ReactiveFormsModule, Validators} from '@angular/forms';
import { Auth } from '../../services/auth';

@Component({
  selector: 'app-registro',
  imports: [NgClass,ReactiveFormsModule],
  templateUrl: './registro.html',
  styleUrl: './registro.scss',
})
export class Registro {
  authService = inject(Auth);
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

  formRegistro= new FormGroup({
    ci: new FormControl('',
      [Validators.required]
    ),

    nombre: new FormControl('',
      
      [Validators.required]
    
    ),
    apellido: new FormControl('',
      
      [Validators.required]
    
    ),
    fechaNac: new FormControl('',
      
      [Validators.required]
    
    ),
    cel: new FormControl('',
      
      [Validators.required]
    
    ),
    email: new FormControl('',
      
      [Validators.required]
    
    ),
    pass: new FormControl('',
      
      [Validators.required]
    
    ),
    repetirPass: new FormControl('',
      
      [Validators.required]
    
    )
  });

  registro(){
    let datos =this.formRegistro.value;
      //debo verificar campos quie no sean vacios u otros que sea in o string o email validos
      
  }

}
