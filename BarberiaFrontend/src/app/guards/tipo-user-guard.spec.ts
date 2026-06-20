import { TestBed } from '@angular/core/testing';
import { CanActivateFn } from '@angular/router';

import { tipoUserGuard } from './tipo-user-guard';

describe('tipoUserGuard', () => {
  const executeGuard: CanActivateFn = (...guardParameters) =>
    TestBed.runInInjectionContext(() => tipoUserGuard(...guardParameters));

  beforeEach(() => {
    TestBed.configureTestingModule({});
  });

  it('should be created', () => {
    expect(executeGuard).toBeTruthy();
  });
});
