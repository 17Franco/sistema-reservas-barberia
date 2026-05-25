import { Routes } from '@angular/router';
import { LoginRegistro } from './pages/login-registro/login-registro';
import { Home } from './pages/home/home';
import { authGuard } from './guards/auth-guard';
import { MiProfile } from './pages/mi-profile/mi-profile';

//aca agregamos el path para que si en el navegador busca home redirija a page home o login etc
//cada pagina debe tener una ruta
export const routes: Routes = [
  {
    path: '', component: Home, canActivate: [authGuard], children:[
      { path: 'MiPerfil', component: MiProfile }
    ]},
  {
    path: 'auth',component: LoginRegistro
  },
  
];
