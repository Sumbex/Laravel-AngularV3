import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { FormularioBeneficiosAuthSocioComponent } from './formulario-beneficios-auth-socio.component';

describe('FormularioBeneficiosAuthSocioComponent', () => {
  let component: FormularioBeneficiosAuthSocioComponent;
  let fixture: ComponentFixture<FormularioBeneficiosAuthSocioComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ FormularioBeneficiosAuthSocioComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(FormularioBeneficiosAuthSocioComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
