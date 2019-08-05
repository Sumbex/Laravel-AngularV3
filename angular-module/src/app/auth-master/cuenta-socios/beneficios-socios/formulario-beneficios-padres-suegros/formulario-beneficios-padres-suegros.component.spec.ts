import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { FormularioBeneficiosPadresSuegrosComponent } from './formulario-beneficios-padres-suegros.component';

describe('FormularioBeneficiosPadresSuegrosComponent', () => {
  let component: FormularioBeneficiosPadresSuegrosComponent;
  let fixture: ComponentFixture<FormularioBeneficiosPadresSuegrosComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ FormularioBeneficiosPadresSuegrosComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(FormularioBeneficiosPadresSuegrosComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
