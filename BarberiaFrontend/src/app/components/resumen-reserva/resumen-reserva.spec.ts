import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ResumenReserva } from './resumen-reserva';

describe('ResumenReserva', () => {
  let component: ResumenReserva;
  let fixture: ComponentFixture<ResumenReserva>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [ResumenReserva],
    }).compileComponents();

    fixture = TestBed.createComponent(ResumenReserva);
    component = fixture.componentInstance;
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
