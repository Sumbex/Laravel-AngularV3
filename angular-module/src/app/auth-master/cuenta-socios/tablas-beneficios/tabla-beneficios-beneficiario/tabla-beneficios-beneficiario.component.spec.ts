import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { TablaBeneficiosBeneficiarioComponent } from './tabla-beneficios-beneficiario.component';

describe('TablaBeneficiosBeneficiarioComponent', () => {
  let component: TablaBeneficiosBeneficiarioComponent;
  let fixture: ComponentFixture<TablaBeneficiosBeneficiarioComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ TablaBeneficiosBeneficiarioComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(TablaBeneficiosBeneficiarioComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
