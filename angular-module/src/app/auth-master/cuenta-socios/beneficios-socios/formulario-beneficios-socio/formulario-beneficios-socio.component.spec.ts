import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { FormularioBeneficiosSocioComponent } from './formulario-beneficios-socio.component';

describe('FormularioBeneficiosSocioComponent', () => {
  let component: FormularioBeneficiosSocioComponent;
  let fixture: ComponentFixture<FormularioBeneficiosSocioComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ FormularioBeneficiosSocioComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(FormularioBeneficiosSocioComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
