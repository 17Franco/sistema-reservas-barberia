import { Injectable,  inject } from '@angular/core';
import { HttpClient } from '@angular/common/http';

@Injectable({
  providedIn: 'root',
})
export class Auth {

  private http = inject(HttpClient);

  private apiUrl='http://localhost:8080/sistema-reservas-barberia/ProyectoBarberiaBackend/public/index.php';

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

}
