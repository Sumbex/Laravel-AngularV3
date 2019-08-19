import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { TablaBeneficiosCargasAuthSocioComponent } from './tabla-beneficios-cargas-auth-socio.component';

describe('TablaBeneficiosCargasAuthSocioComponent', () => {
  let component: TablaBeneficiosCargasAuthSocioComponent;
  let fixture: ComponentFixture<TablaBeneficiosCargasAuthSocioComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ TablaBeneficiosCargasAuthSocioComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(TablaBeneficiosCargasAuthSocioComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
