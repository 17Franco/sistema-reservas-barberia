import { Injectable, inject } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { environment } from '../../environments/environment';

export interface Resena {
  idResena?: number;
  idCliente?: number;
  idEmpleado: number;
  puntuacion: number;
  comentario?: string;
  fechaCreacion?: string;

  clienteNombre?: string;
  clienteApellido?: string;
  barberoNombre?: string;
  barberoApellido?: string;
}

@Injectable({
  providedIn: 'root'
})
export class ResenasService {

  private http = inject(HttpClient);

  private apiUrl = environment.apiUrl;

listarResenas() {
  return this.http.get<any>(
    `${this.apiUrl}/resenas`,
    { withCredentials: true }
  );
}

  crearResena(resena: Resena) {
    return this.http.post<any>(
      `${this.apiUrl}/resenas`,
      resena,
      { withCredentials: true }
    );
  }

  eliminarResena(idResena: number) {
    return this.http.delete<any>(
      `${this.apiUrl}/resenas/${idResena}`,
      { withCredentials: true }
    );
  }
}
