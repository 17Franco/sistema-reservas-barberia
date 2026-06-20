import { Routes } from '@angular/router';
import { LoginRegistro } from './pages/login-registro/login-registro';
import { Home } from './pages/home/home';
import { authGuard } from './guards/auth-guard';
import { Servicios } from './pages/servicios/servicios';
import { PaginaPerfil } from './pages/pagina-perfil/pagina-perfil';
import { PantallaPrincipal } from './pages/pantalla-principal/pantalla-principal'; // importamos la pantalla principal para agregarla a las rutas.

//nuevos imports para las rutas de admin.
import { AdminDashboard } from './pages/admin/admin-dashboard/admin-dashboard';
import { GestionServicios } from './pages/admin/gestion-servicios/gestion-servicios';
import { GestionBarberos } from './pages/admin/gestion-barberos/gestion-barberos';
import { GestionReserva } from './pages/admin/gestion-reserva/gestion-reserva';
import { Reserva } from './pages/reserva/reserva';
import { tipoUserGuard } from './guards/tipo-user-guard';

//aca agregamos el path para que si en el navegador busca home redirija a page home o login etc
//cada pagina debe tener una ruta
export const routes: Routes = [
  {
    path: '',
    component: Home,
    canActivate: [authGuard],
    children: [
      {
        path: '',
        component: PantallaPrincipal
      },
      {
        path: 'servicios',
        component: Servicios
      },
      {
        path: 'MiPerfil',
        component: PaginaPerfil
      },
      {
        path: 'admin',
        component: AdminDashboard,
        canActivate: [tipoUserGuard]
      },
      {
        path: 'admin/servicios',
        component: GestionServicios,
        canActivate: [tipoUserGuard]
      },
      {
        path: 'admin/barberos',
        component: GestionBarberos,
        canActivate: [tipoUserGuard]
      },
      {
        path: 'admin/reservas',
        component: GestionReserva,
        canActivate: [tipoUserGuard] 
      },
      {
        path: 'reservar',
        component: Reserva,
        canActivate: [] 
      }
    ]
  },
  {
    path: 'auth',
    component: LoginRegistro
  }
];
