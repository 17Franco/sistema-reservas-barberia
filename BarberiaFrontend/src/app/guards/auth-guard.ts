import { CanActivateFn, Router } from '@angular/router';
import { inject } from '@angular/core';

export const authGuard: CanActivateFn = () => {

  const auth =true;
  const router = inject(Router);

  if (!auth) {
    
    return router.createUrlTree(['/auth']);
  }

  return true
  

  
};
