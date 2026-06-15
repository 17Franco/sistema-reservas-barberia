import { ComponentFixture, TestBed } from '@angular/core/testing';

import { GestionBarberos } from './gestion-barberos';

describe('GestionBarberos', () => {
  let component: GestionBarberos;
  let fixture: ComponentFixture<GestionBarberos>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [GestionBarberos],
    }).compileComponents();

    fixture = TestBed.createComponent(GestionBarberos);
    component = fixture.componentInstance;
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
