import { Component, inject } from '@angular/core';
import { Router } from '@angular/router';
import { Auth } from '../../services/auth';
import {FormGroup,FormControl, ReactiveFormsModule, Validators} from '@angular/forms';
import { ChangeDetectorRef } from '@angular/core';


@Component({
  selector: 'app-login',
  imports: [ReactiveFormsModule],
  templateUrl: './login.html',
  styleUrl: './login.scss',
})
export class Login {

  authService = inject(Auth); // injecto el servicio
  router = inject(Router);
  cd = inject(ChangeDetectorRef);
  mensajeError: string = '';

  formLogin = new FormGroup({
    email: new FormControl('',
      [Validators.required,
        validarEmail 
      ],
      
    ),
    contraseña: new FormControl('',
      
      [Validators.required]
    
    )
  });

  login(){
      this.mensajeError='';

      if(this.formLogin.invalid){
        return;
      }

      let datos =this.formLogin.value;
      //console.log(datos);
      
      this.authService.login(datos).subscribe({
        next:(res:any) => {
          if(res.success){
            //console.log(res);
            
            if (res.tipo === 'ADMIN') {
                this.router.navigate(['/admin']);
            } else if(res.tipo === 'CLIENTE') {
                this.router.navigate(['/']);
            }
           // this.router.navigateByUrl('/')
          }
        },
        error:(err)=>{
          //console.log(err);
          if(err.status === 401){
            
            this.mensajeError="Usuario o Contraseña incorrectos";
            this.cd.detectChanges();
            //console.log(this.mensajeError)
            setTimeout(()=>{
              this.mensajeError = '';
              this.cd.detectChanges();
            },3000);
          }
      }
        
      });
    }

   obtenerError(){

      let control = this.formLogin.get('email');

      if(!control?.touched){
        return '';
      }

      if(control.errors?.['required']){
        return 'El campo no puede ser vacio.';
      }

      if(control.errors?.['usuarioInvalido']){
        return 'Ingrese un email válido';
      }


      return '';
   }

    campoInvalido(nombre:string){
      const campo = this.formLogin.get(nombre);

     return (campo?.invalid && (campo?.touched ||  campo?.dirty)) ;
    }
}

function validarEmail(control:any){
  let valor = control.value;
  const emailValido = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  const ciRegex = /^\d{7,8}$/;
  
  if (!valor) {
    return null;
  }

  //si no hay problema devuelvo null
  if(emailValido.test(valor)){
    return null;
  }

  //si hay probblema devulvo true el usuarioInvalido es el nombre que se guarda en errors 
  return { usuarioInvalido:true };

}