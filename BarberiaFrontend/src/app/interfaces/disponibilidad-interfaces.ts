export interface DisponibilidadInterfaces{

    
}

export interface Dia {
    fecha: string;
    dia: string;
    numeroDia: number;
    mes: string;
    disponible: boolean;
}

export interface ApiResponse {
    success: boolean;
    data: Dia[];
}
export interface ResponseServicioDisponible {
    success: boolean;
    data: Servicio[];
}

export interface Servicio {
  idServicio: number;
  disponible: boolean;
  duracion: number;
  nombre: string;
  descripcion: string;
  precio: number;
}

export interface EmpleadoD { 
  estado: string;
  disponible: boolean;
  especialidad: string;
  horarios: string[];
  id: number;
  ci: string;
  nombre: string;
  apellido: string;
  fechaNac: string;
  pass: string;
  email: string;
  foto: string;
  cel: string;
}

export interface ResponseEmpleado {
    success: boolean;
    data: EmpleadoD[];
}

export interface Horario {
  inicio: string;
  fin: string;
}

export interface ResponseHorarios {
  success: boolean;
  data: Horario[];
}