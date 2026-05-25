import { ComponentFixture, TestBed } from '@angular/core/testing';

import { MiProfile } from './mi-profile';

describe('MiProfile', () => {
  let component: MiProfile;
  let fixture: ComponentFixture<MiProfile>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [MiProfile],
    }).compileComponents();

    fixture = TestBed.createComponent(MiProfile);
    component = fixture.componentInstance;
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
