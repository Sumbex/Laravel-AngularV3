import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { InteresPrestamoApuroEconomicoComponent } from './interes-prestamo-apuro-economico.component';

describe('InteresPrestamoApuroEconomicoComponent', () => {
  let component: InteresPrestamoApuroEconomicoComponent;
  let fixture: ComponentFixture<InteresPrestamoApuroEconomicoComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ InteresPrestamoApuroEconomicoComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(InteresPrestamoApuroEconomicoComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
