import { ComponentFixture, TestBed } from '@angular/core/testing';

import { GestionReserva } from './gestion-reserva';

describe('GestionReserva', () => {
  let component: GestionReserva;
  let fixture: ComponentFixture<GestionReserva>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [GestionReserva],
    }).compileComponents();

    fixture = TestBed.createComponent(GestionReserva);
    component = fixture.componentInstance;
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
