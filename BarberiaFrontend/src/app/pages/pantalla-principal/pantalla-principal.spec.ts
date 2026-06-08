import { ComponentFixture, TestBed } from '@angular/core/testing';

import { PantallaPrincipal } from './pantalla-principal';

describe('PantallaPrincipal', () => {
  let component: PantallaPrincipal;
  let fixture: ComponentFixture<PantallaPrincipal>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [PantallaPrincipal],
    }).compileComponents();

    fixture = TestBed.createComponent(PantallaPrincipal);
    component = fixture.componentInstance;
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
