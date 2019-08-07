import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { FormularioBeneficiosCargasAuthSocioComponent } from './formulario-beneficios-cargas-auth-socio.component';

describe('FormularioBeneficiosCargasAuthSocioComponent', () => {
  let component: FormularioBeneficiosCargasAuthSocioComponent;
  let fixture: ComponentFixture<FormularioBeneficiosCargasAuthSocioComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ FormularioBeneficiosCargasAuthSocioComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(FormularioBeneficiosCargasAuthSocioComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
