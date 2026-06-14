import { Injectable,  inject } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { RegistroUsuario } from '../interfaces/registro-usuario';

@Injectable({
  providedIn: 'root',
})
export class Auth {

  private http = inject(HttpClient);
  usuario:any=null;
  

  private apiUrl='http://localhost/sistema-reservas-barberia/ProyectoBarberiaBackend/public/index.php';

  me(){
    return this.http.get(
   `${this.apiUrl }/me`,
   {
      withCredentials:true
   }
  );
  }

  login(datos:any){

    return this.http.post(
      `${this.apiUrl}/login`,
      datos,
      {
        withCredentials:true
      }
    );

  }

  logOut(){
    return this.http.post(
    `${this.apiUrl }/logout`,
    {},
    {
        withCredentials:true
    }
    );
  }

  guardarUsuario(usuario:string, nombre:string, tipo:number){
    this.usuario = {
      usuario: usuario,
      nombre: nombre,
      tipo: tipo
    };
  }

  registrarUsuario(datos:FormData){
    console.log("llego");
    return this.http.post(`${this.apiUrl}/usuarios`, datos);
  }


  getReservasClienteAsociado(idCliente: number){
    console.log("llegaon las reservas");
    return this.http.get<any>(
      `${this.apiUrl}/reservasClienteAsociado/${idCliente}`,
      {
        withCredentials: true
      }
    )
  };


  editarPerfilUsuario(datos: { nombre: string; apellido: string; celular: string }){
    return this.http.put<any>(
      `${this.apiUrl}/editarPerfil`,
      datos,
      {
        withCredentials: true
      }
    )
  };

  




}
