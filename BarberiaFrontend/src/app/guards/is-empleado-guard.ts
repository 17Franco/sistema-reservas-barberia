import { inject } from '@angular/core';
import { CanActivateFn, Router } from '@angular/router';
import { Auth } from '../services/auth';

export const isEmpleadoGuard: CanActivateFn = (route, state) => {
  const authService = inject(Auth); // injecto el servicio
  const router = inject(Router);

  if (authService.usuario?.tipo === 'EMPLEADO') {
    return true;
  }

  //si admin intenta entrar redirigo a dashboard si es es cliente a inicio normal si es empleado refirige a donde queria entrar osea front de empleado
  if( authService.usuario?.tipo === 'ADMIN'){
    return router.createUrlTree(['/admin']);
  }
  
  return router.createUrlTree(['/']);
};
