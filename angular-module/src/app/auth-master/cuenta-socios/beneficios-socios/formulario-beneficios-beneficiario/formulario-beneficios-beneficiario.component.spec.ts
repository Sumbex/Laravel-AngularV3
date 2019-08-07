import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { FormularioBeneficiosBeneficiarioComponent } from './formulario-beneficios-beneficiario.component';

describe('FormularioBeneficiosBeneficiarioComponent', () => {
  let component: FormularioBeneficiosBeneficiarioComponent;
  let fixture: ComponentFixture<FormularioBeneficiosBeneficiarioComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ FormularioBeneficiosBeneficiarioComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(FormularioBeneficiosBeneficiarioComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
