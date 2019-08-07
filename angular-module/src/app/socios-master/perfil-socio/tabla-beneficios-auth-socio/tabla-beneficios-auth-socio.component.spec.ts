import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { TablaBeneficiosAuthSocioComponent } from './tabla-beneficios-auth-socio.component';

describe('TablaBeneficiosAuthSocioComponent', () => {
  let component: TablaBeneficiosAuthSocioComponent;
  let fixture: ComponentFixture<TablaBeneficiosAuthSocioComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ TablaBeneficiosAuthSocioComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(TablaBeneficiosAuthSocioComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
