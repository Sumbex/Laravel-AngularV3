import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { TablaBeneficiosBeneficiarioAuthSocioComponent } from './tabla-beneficios-beneficiario-auth-socio.component';

describe('TablaBeneficiosBeneficiarioAuthSocioComponent', () => {
  let component: TablaBeneficiosBeneficiarioAuthSocioComponent;
  let fixture: ComponentFixture<TablaBeneficiosBeneficiarioAuthSocioComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ TablaBeneficiosBeneficiarioAuthSocioComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(TablaBeneficiosBeneficiarioAuthSocioComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
