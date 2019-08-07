import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { FormularioBeneficiosCargasComponent } from './formulario-beneficios-cargas.component';

describe('FormularioBeneficiosCargasComponent', () => {
  let component: FormularioBeneficiosCargasComponent;
  let fixture: ComponentFixture<FormularioBeneficiosCargasComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ FormularioBeneficiosCargasComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(FormularioBeneficiosCargasComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
