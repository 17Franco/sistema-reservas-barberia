import { inject, Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { map, Observable } from 'rxjs';
import { ApiResponse, Dia, EmpleadoD, Horario, ResponseEmpleado, ResponseHorarios, ResponseServicioDisponible, Servicio } from '../../interfaces/disponibilidad-interfaces';



@Injectable({
  providedIn: 'root',
})
export class Disponibilidad {
  private http = inject(HttpClient);
  private apiUrl='http://localhost/sistema-reservas-barberia/ProyectoBarberiaBackend/public/index.php';

  calendario():Observable<Dia[]>{
    return this.http.get<ApiResponse>(`${this.apiUrl }/disponibilidad`,
   {
      withCredentials:true
   }).pipe(
    map(res => res.data)
   );

  }

  disponibilidadServicioDia(fecha: String):Observable<Servicio[]>{
    return this.http.get<ResponseServicioDisponible>(`${this.apiUrl }/servicio/disponibilidad?fecha=${fecha}`,
    {
      withCredentials:true
    }).pipe(
    map(res => res.data)
   );
  }

  disponibilidadEmpleadoDia(fecha: String,idServicio:number):Observable<EmpleadoD[]>{
    return this.http.get<ResponseEmpleado>(`${this.apiUrl }/empleado/disponibilidad?fecha=${fecha}&id=${idServicio}`,
    {
      withCredentials:true
    }).pipe(
    map(res => res.data)
   );
  }

  disponibilidadHorario(fecha: String,idServicio:number,idEmpleado:number):Observable<Horario[]>{
    return this.http.get<ResponseHorarios>(`${this.apiUrl }/disponibilidadHorarios?fecha=${fecha}&id=${idServicio}&idE=${idEmpleado}`,
    {
      withCredentials:true
    }).pipe(
    map(res => res.data)
   );
  }
}
