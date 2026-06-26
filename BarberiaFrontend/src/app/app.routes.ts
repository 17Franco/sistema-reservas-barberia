import { Routes } from '@angular/router';
import { LoginRegistro } from './pages/login-registro/login-registro';
import { Home } from './pages/home/home';
import { authGuard } from './guards/auth-guard';
import { Servicios } from './pages/servicios/servicios';
import { PaginaPerfil } from './pages/pagina-perfil/pagina-perfil';
import { PantallaPrincipal } from './pages/pantalla-principal/pantalla-principal'; // importamos la pantalla principal para agregarla a las rutas.
import { PerfilBarbero } from './pages/perfil-barbero/perfil-barbero';
import { ServiciosBarbero } from './pages/admin/servicios-barbero/servicios-barbero';

//nuevos imports para las rutas de admin.
import { AdminDashboard } from './pages/admin/admin-dashboard/admin-dashboard';
import { GestionServicios } from './pages/admin/gestion-servicios/gestion-servicios';
import { GestionBarberos } from './pages/admin/gestion-barberos/gestion-barberos';
import { GestionReserva } from './pages/admin/gestion-reserva/gestion-reserva';
import { Reserva } from './pages/reserva/reserva';
import { tipoUserGuard } from './guards/tipo-user-guard';

import { GestionResenas } from './pages/admin/gestion-resenas/gestion-resenas';


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
        component: PantallaPrincipal,
        canActivate: [tipoUserGuard],
        data: {
          roles: ['CLIENTE']
        }
      },
      {
        path: 'servicios',
        component: Servicios,
        canActivate: [tipoUserGuard],
        data: {
          roles: ['CLIENTE']
        }
      },
      {
        path: 'MiPerfil',
        component: PaginaPerfil,
        canActivate: [tipoUserGuard],
        data: {
          roles: ['CLIENTE']
        }
      },
      {
        path: 'perfilBarbero',
        component: PerfilBarbero,
        canActivate: [tipoUserGuard],
        data: {
          roles: ['EMPLEADO']
        }
      },
      {
        path: 'admin',
        component: AdminDashboard,
        canActivate: [tipoUserGuard],
        data: {
          roles: ['ADMIN']
        }
      },
      {
        path: 'admin/servicios',
        component: GestionServicios,
        canActivate: [tipoUserGuard],
        data: {
          roles: ['ADMIN']
        }
      },
      {
        path: 'admin/servicios-barbero',
        component: ServiciosBarbero,
        canActivate: [tipoUserGuard],
        data: {
          roles: ['ADMIN']
        }
      },
      {
        path: 'admin/barberos',
        component: GestionBarberos,
        canActivate: [tipoUserGuard],
         data: {
          roles: ['ADMIN']
        }
      },
      {
        path: 'admin/reservas',
        component: GestionReserva,
        canActivate: [tipoUserGuard],
         data: {
          roles: ['ADMIN']
        }
      },
      {
        path: 'reservar',
        component: Reserva,
        canActivate: [tipoUserGuard],
        data: {
          roles: ['CLIENTE']
        }
      },
      {
        path: 'admin/resenas',
        component: GestionResenas,
        canActivate: [tipoUserGuard],
        data: {
          roles: ['ADMIN']
        }
      },
      {
        path: 'resenas',
        component: GestionResenas,
        canActivate: [tipoUserGuard],
        data: {
          roles: ['CLIENTE', 'ADMIN']
        }
      }
    ]
  },
  {
    path: 'auth',
    component: LoginRegistro
  }
];
