import { TestBed } from '@angular/core/testing';
import { CanActivateFn } from '@angular/router';

import { isEmpleadoGuard } from './is-empleado-guard';

describe('isEmpleadoGuard', () => {
  const executeGuard: CanActivateFn = (...guardParameters) =>
    TestBed.runInInjectionContext(() => isEmpleadoGuard(...guardParameters));

  beforeEach(() => {
    TestBed.configureTestingModule({});
  });

  it('should be created', () => {
    expect(executeGuard).toBeTruthy();
  });
});
