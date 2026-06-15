import { ComponentFixture, TestBed } from '@angular/core/testing';

import { HorarioDisponibles } from './horario-disponibles';

describe('HorarioDisponibles', () => {
  let component: HorarioDisponibles;
  let fixture: ComponentFixture<HorarioDisponibles>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [HorarioDisponibles],
    }).compileComponents();

    fixture = TestBed.createComponent(HorarioDisponibles);
    component = fixture.componentInstance;
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
