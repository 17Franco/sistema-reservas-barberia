import { Routes } from '@angular/router';
import { LoginRegistro } from './pages/login-registro/login-registro';
import { Home } from './pages/home/home';
import { authGuard } from './guards/auth-guard';
import { Servicios } from './pages/servicios/servicios';
import { PaginaPerfil } from './pages/pagina-perfil/pagina-perfil';

//aca agregamos el path para que si en el navegador busca home redirija a page home o login etc
//cada pagina debe tener una ruta
export const routes: Routes = [
  {
    path: '', component: Home, canActivate: [authGuard], children:[
      { path: 'MiPerfil', component: PaginaPerfil }
    ]},
  {
    path: 'servicios', component: Servicios, canActivate: [authGuard]
  },
  {
    path: 'auth',component: LoginRegistro
  },
];
