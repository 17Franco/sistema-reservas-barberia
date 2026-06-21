import { Injectable, inject } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { RegistroUsuario } from '../interfaces/registro-usuario';

@Injectable({
  providedIn: 'root',
})
export class Auth {

  private http = inject(HttpClient);
  usuario:any=null;       //uso esta para comunicar nav-bar con pagina-perfil
  

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

  guardarUsuario(usuario:string, nombre:string, tipo:string){
    this.usuario = {
      usuario: usuario,
      nombre: nombre,
      tipo: tipo
    };
  }

  actualizarUsuario(datos: Record<string, unknown>): void {
    this.usuario = {
      ...(this.usuario || {}),
      ...datos,
    };
  }

  registrarUsuario(datos:FormData){
    //console.log("llego");
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

  getReservasBarberoAsociado(idBarbero: number){
    console.log("llegaon las reservas");
    return this.http.get<any>(
      `${this.apiUrl}/reservasBarberoAsociado/${idBarbero}`,
      {
        withCredentials: true
      }
    )
  };

  getBarberos(){
    return this.http.get<any>(`${this.apiUrl}/empleados`, { withCredentials: true });
  }

  crearBarbero(datos: any){
    return this.http.post<any>(`${this.apiUrl}/empleados`, datos, { withCredentials: true });
  }

  actualizarBarbero(id: number, datos: any){
    return this.http.put<any>(`${this.apiUrl}/empleados/${id}`, datos, { withCredentials: true });
  }

  cambiarEstadoBarbero(ci: string, estado: 'ACTIVO' | 'INACTIVO'){
    return this.http.put<any>(`${this.apiUrl}/empleados/estado`, { ci, estado }, { withCredentials: true });
  }


  editarPerfilUsuario(datos: FormData){
    return this.http.post<any>(
      `${this.apiUrl}/editarPerfil`,
      datos,
      {
        withCredentials: true
      }
    )
  };

  validarEmail(email:string){
    return this.http.get<any>(`${this.apiUrl}/usuarios/validar-email?email=${email}`)
  }

   validarCi(ci:string){
    return this.http.get<any>(`${this.apiUrl}/usuarios/validar-ci?ci=${ci}`)
  }


  cancelarReserva(idReserva: number) {
    return this.http.put<any>(
      `${this.apiUrl}/reservas/${idReserva}/cancelar`,
      {},
      {
        withCredentials: true,
      }
    );
  }

  confirmarReserva(idReserva: number) {
    return this.http.put<any>(
      `${this.apiUrl}/reservas/${idReserva}/confirmar`,
      {},
      {
        withCredentials: true,
      }
    );
  }

  completarReserva(idReserva: number) {
    return this.http.put<any>(
      `${this.apiUrl}/reservas/${idReserva}/completar`,
      {},
      {
        withCredentials: true,
      }
    );
  }

}
