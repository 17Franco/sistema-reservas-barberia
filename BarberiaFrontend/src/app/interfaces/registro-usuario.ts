export interface RegistroUsuario {
    //lo dejo pero no se usas 
    ci: string,
    nombre: string,
    apellido: string,
    fecha: string,
    cel: string,
    email:string,
    pass:string,
    foto: File | null;
}
