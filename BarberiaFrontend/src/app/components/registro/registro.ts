import { Component, EventEmitter, inject, Output } from '@angular/core';
import { NgClass } from '@angular/common';
import {FormGroup,FormControl, ReactiveFormsModule, Validators} from '@angular/forms';
import { Auth } from '../../services/auth';
import { RegistroUsuario } from '../../interfaces/registro-usuario';
import Swal from 'sweetalert2';

@Component({
  selector: 'app-registro',
  imports: [NgClass,ReactiveFormsModule],
  templateUrl: './registro.html',
  styleUrl: './registro.scss',
})
export class Registro {
  authService = inject(Auth);
  step: number = 1;

  @Output()
  volverLogin = new EventEmitter<void>();

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
      [Validators.required,
       Validators.pattern(/^\d{8}$/)
      ]
    ),

    nombre: new FormControl('',
      
      [Validators.required]
    
    ),
    apellido: new FormControl('',
      
      [Validators.required]
    
    ),
    fechaNac: new FormControl('',
      
      [Validators.required,
        validadFecha
      ]
    
    ),
    cel: new FormControl('',
      
      [Validators.required,
       Validators.pattern(/^09\d{7}$/)
      ]
    
    ),
    email: new FormControl('',
      
      [Validators.required,
      ]
    
    ),
    pass: new FormControl('',
      
      [Validators.required]
    
    ),
    foto: new FormControl(null,
       
    ),
    repetirPass: new FormControl('',
      
      [Validators.required]
    
    )
  });

  registro(){
    if(this.formRegistro.invalid){
      return
    }
   
    if(this.formRegistro.valid){
      let datos =this.formRegistro.value;
        
      const formData = new FormData();

      formData.append('ci', datos.ci!);
      formData.append('nombre', datos.nombre!);
      formData.append('apellido', datos.apellido!);
      formData.append('fechaNac', datos.fechaNac!);
      formData.append('cel', datos.cel!);
      formData.append('email', datos.email!);
      formData.append('pass', datos.pass!);
      if(datos.foto){
        formData.append('foto', datos.foto);
      }

      this.authService.registrarUsuario(formData).subscribe({
        next: (res:any) => {
          if(res){
            console.log("Registro Realizado correctamente");
            //si todo salio bien muestro 
            Swal.fire({
              title: 'Cliente Registrado',
              text: 'Cuenta creada correctamente',
              icon: 'success'
            }).then( (result) =>{
              if(result.isConfirmed){
                this.volverLogin.emit();
              }
            })
            
          } 
        },
        error: (err) => {
            console.error(err);

          }
      });
    };
    
    //debo verificar campos quie no sean vacios u otros que sea in o string o email validos 
  }

  onFileChange(event: any) {

    const file = event.target.files[0];

    this.formRegistro.patchValue({
      foto: file
    });
  }


  obtenerMensajeError(campo:string){
      let control = this.formRegistro.get(campo);

      if(control?.hasError('required')){
        return 'Debe completar el campo';
      }

      if(control?.hasError('pattern')){
        
        if(campo == 'ci'){
          return 'Ingrese una cédula válida';
        }

        if(campo == 'cel'){
          return 'Ingrese un celular válido';
        }
      }
      
      if(control?.hasError('emailExistente')){
        return 'Ya existe cuenta asociada a este email';
      }
       if(control?.hasError('ciExistente')){
        return 'Ya existe cuenta asociada';
      }
      if(control?.hasError('edadMinima')){
        return 'Debe ser mayor a 12';
      }
      if(control?.hasError('edadMaxima')){
        return 'Debe ser menor de 100';
      }
      


      return '';
  } 

   
    btndisabled(){
      return this.formRegistro.invalid;

    }
    campoInvalido(nombre:string){
        const campo = this.formRegistro.get(nombre);

        return (campo?.invalid && (campo?.touched ||  campo?.dirty)) ;
        
    }
    peticionValidarEmail(){
      const control = this.formRegistro.get('email');
      if (control?.invalid || control == null) {
        return;
      }
      const email = control.value || "";
      this.authService.validarEmail(email).subscribe({
        next:(res:any) =>{
          //console.log(res);
          if(res.existe){
            //intento agregar el error si junto con los que ya podria tener 
            control.setErrors({
              ...(control.errors ?? {}),
              emailExistente: true
            });
          }else{
            //quito el error especifico 
            const errores = control?.errors;

            if (errores) {
              delete errores['emailExistente'];

              control?.setErrors(
                Object.keys(errores).length ? errores : null
              );
            }
          }
          
        },
        error: (err:any) =>{
          console.log(err);
        }
      })
    }

     peticionValidarCi(){
      const control = this.formRegistro.get('ci');
      if (control?.invalid || control == null) {
        return;
      }
      const ci = control.value || "";
      this.authService.validarCi(ci).subscribe({
        next:(res:any) =>{
          //console.log(res);
          if(res.existe){
            //intento agregar el error si junto con los que ya podria tener 
            control.setErrors({
              ...(control.errors ?? {}),
              ciExistente: true
            });
          }else{
            //quito el error especifico 
            const errores = control?.errors;

            if (errores) {
              delete errores['ciExistente'];

              control?.setErrors(
                Object.keys(errores).length ? errores : null
              );
            }
          }
          
        },
        error: (err:any) =>{
          console.log(err);
        }
      })
    }


}



function validadFecha(control:any){
  //nesesito que minmio tenga 12 anios 
  //que no tenga mas de 100 
  let fechaNacimiento = new Date(control.value);
  let hoy = new Date();

  let edad = hoy.getFullYear() - fechaNacimiento.getFullYear();

  if(edad < 12){
    return {edadMinima:true};
  }else if(edad > 100){
    return{edadMaxima:true};
  }

  return null;
}

function validarEmail(usado:boolean){
  
  
}