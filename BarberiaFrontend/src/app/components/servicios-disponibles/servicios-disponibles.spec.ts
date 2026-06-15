import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ServiciosDisponibles } from './servicios-disponibles';

describe('ServiciosDisponibles', () => {
  let component: ServiciosDisponibles;
  let fixture: ComponentFixture<ServiciosDisponibles>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [ServiciosDisponibles],
    }).compileComponents();

    fixture = TestBed.createComponent(ServiciosDisponibles);
    component = fixture.componentInstance;
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
