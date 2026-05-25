import { CanActivateFn, Router } from '@angular/router';
import { inject } from '@angular/core';
import { Auth } from '../services/auth';
import { catchError, map, of } from 'rxjs';

export const authGuard: CanActivateFn = () => {

  const authService = inject(Auth); // injecto el servicio
  const router = inject(Router);

  //llamo a funcion .me del servicio
  return authService.me().pipe(
    map((respuesta: any) => {

    if (respuesta.logueado) {
      console.log(respuesta);
      return true;
    }

    return router.createUrlTree(['/auth']);

    }),

    //si ocurre algo malo mando al login tambien 
    catchError(() => {
      return of(router.createUrlTree(['/auth']));
    })
  );

  
  

  
};
