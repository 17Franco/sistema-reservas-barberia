import { inject } from '@angular/core';
import { CanActivateFn, Router } from '@angular/router';
import { Auth } from '../services/auth';

export const tipoUserGuard: CanActivateFn = (route, state) => {

  const authService = inject(Auth); // injecto el servicio
  const router = inject(Router);

  if (authService.usuario?.tipo === 'ADMIN') {
    return true;
  }

  if( authService.usuario?.tipo === 'EMPLEADO'){
    return router.createUrlTree(['/perfilBarbero']);
  }
  
  return router.createUrlTree(['/']);
  //return true;
};
