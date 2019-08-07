import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { FormularioBeneficiosConyugeAuthSocioComponent } from './formulario-beneficios-conyuge-auth-socio.component';

describe('FormularioBeneficiosConyugeAuthSocioComponent', () => {
  let component: FormularioBeneficiosConyugeAuthSocioComponent;
  let fixture: ComponentFixture<FormularioBeneficiosConyugeAuthSocioComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ FormularioBeneficiosConyugeAuthSocioComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(FormularioBeneficiosConyugeAuthSocioComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
