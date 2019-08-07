import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { FormularioBeneficiosBeneficiarioAuthSocioComponent } from './formulario-beneficios-beneficiario-auth-socio.component';

describe('FormularioBeneficiosBeneficiarioAuthSocioComponent', () => {
  let component: FormularioBeneficiosBeneficiarioAuthSocioComponent;
  let fixture: ComponentFixture<FormularioBeneficiosBeneficiarioAuthSocioComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ FormularioBeneficiosBeneficiarioAuthSocioComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(FormularioBeneficiosBeneficiarioAuthSocioComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
