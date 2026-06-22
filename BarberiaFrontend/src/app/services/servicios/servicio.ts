import { Injectable, inject } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { environment } from '../../../environments/environment';

export interface Servicio {
  idServicio?: number;
  nombre: string;
  descripcion: string;
  duracion: number;
  precio: number;
}

@Injectable({
  providedIn: 'root'
})
export class ServiciosService {

  private http = inject(HttpClient);

  private apiUrl = environment.apiUrl;

  getServicios() {
    return this.http.get<any>(`${this.apiUrl}/servicios`, {
      withCredentials: true
    });
  }

  crearServicio(servicio: Servicio) {
    return this.http.post<any>(`${this.apiUrl}/servicios`, servicio, {
      withCredentials: true
    });
  }

  actualizarServicio(id: number, servicio: Servicio) {
    return this.http.put<any>(`${this.apiUrl}/servicios/${id}`, servicio, {
      withCredentials: true
    });
  }

  eliminarServicio(id: number) {
    return this.http.delete<any>(`${this.apiUrl}/servicios/${id}`, {
      withCredentials: true
    });
  }
}