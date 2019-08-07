import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { FormularioBeneficiosPadresSuegrosAuthSocioComponent } from './formulario-beneficios-padres-suegros-auth-socio.component';

describe('FormularioBeneficiosPadresSuegrosAuthSocioComponent', () => {
  let component: FormularioBeneficiosPadresSuegrosAuthSocioComponent;
  let fixture: ComponentFixture<FormularioBeneficiosPadresSuegrosAuthSocioComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ FormularioBeneficiosPadresSuegrosAuthSocioComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(FormularioBeneficiosPadresSuegrosAuthSocioComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
