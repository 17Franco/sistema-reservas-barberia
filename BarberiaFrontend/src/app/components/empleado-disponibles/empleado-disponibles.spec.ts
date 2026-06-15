import { ComponentFixture, TestBed } from '@angular/core/testing';

import { EmpleadoDisponibles } from './empleado-disponibles';

describe('EmpleadoDisponibles', () => {
  let component: EmpleadoDisponibles;
  let fixture: ComponentFixture<EmpleadoDisponibles>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [EmpleadoDisponibles],
    }).compileComponents();

    fixture = TestBed.createComponent(EmpleadoDisponibles);
    component = fixture.componentInstance;
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
