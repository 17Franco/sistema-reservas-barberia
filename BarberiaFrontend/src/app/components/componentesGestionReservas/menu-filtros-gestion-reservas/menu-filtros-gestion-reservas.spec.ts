import { ComponentFixture, TestBed } from '@angular/core/testing';

import { MenuFiltrosGestionReservas } from './menu-filtros-gestion-reservas';

describe('MenuFiltrosGestionReservas', () => {
  let component: MenuFiltrosGestionReservas;
  let fixture: ComponentFixture<MenuFiltrosGestionReservas>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [MenuFiltrosGestionReservas],
    }).compileComponents();

    fixture = TestBed.createComponent(MenuFiltrosGestionReservas);
    component = fixture.componentInstance;
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
