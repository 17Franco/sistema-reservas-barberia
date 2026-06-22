import { ComponentFixture, TestBed } from '@angular/core/testing';

import { BarraGestionReserva } from './barra-gestion-reserva';

describe('BarraGestionReserva', () => {
  let component: BarraGestionReserva;
  let fixture: ComponentFixture<BarraGestionReserva>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [BarraGestionReserva],
    }).compileComponents();

    fixture = TestBed.createComponent(BarraGestionReserva);
    component = fixture.componentInstance;
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
