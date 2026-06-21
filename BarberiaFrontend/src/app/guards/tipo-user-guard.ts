import { inject } from '@angular/core';
import { CanActivateFn, Router } from '@angular/router';
import { Auth } from '../services/auth';

export const tipoUserGuard: CanActivateFn = (route, state) => {

  const authService = inject(Auth); // injecto el servicio
  const router = inject(Router);

  const roles = route.data['roles'] as string[]; //guardo lo que mando en routes


  if (roles.includes(authService.usuario?.tipo ?? '')) {
    return true;
  }

  //aca como pantalla principal es ditinto entre tipos de usuario debo controlarlo
  if( authService.usuario?.tipo === 'EMPLEADO'){
    return router.createUrlTree(['/perfilBarbero']);
  }else if(authService.usuario?.tipo === 'ADMIN'){
    return router.createUrlTree(['/admin']);
  }
  
  return router.createUrlTree(['/']);
  //return true;
};
