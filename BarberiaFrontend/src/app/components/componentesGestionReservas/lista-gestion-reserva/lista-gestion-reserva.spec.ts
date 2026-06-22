import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ListaGestionReserva } from './lista-gestion-reserva';

describe('ListaGestionReserva', () => {
  let component: ListaGestionReserva;
  let fixture: ComponentFixture<ListaGestionReserva>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [ListaGestionReserva],
    }).compileComponents();

    fixture = TestBed.createComponent(ListaGestionReserva);
    component = fixture.componentInstance;
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
