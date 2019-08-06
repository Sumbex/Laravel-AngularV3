import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { FormularioBeneficiosConyugeComponent } from './formulario-beneficios-conyuge.component';

describe('FormularioBeneficiosConyugeComponent', () => {
  let component: FormularioBeneficiosConyugeComponent;
  let fixture: ComponentFixture<FormularioBeneficiosConyugeComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ FormularioBeneficiosConyugeComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(FormularioBeneficiosConyugeComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
