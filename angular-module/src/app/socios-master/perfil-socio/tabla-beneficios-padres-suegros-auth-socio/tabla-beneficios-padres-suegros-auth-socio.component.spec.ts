import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { TablaBeneficiosPadresSuegrosAuthSocioComponent } from './tabla-beneficios-padres-suegros-auth-socio.component';

describe('TablaBeneficiosPadresSuegrosAuthSocioComponent', () => {
  let component: TablaBeneficiosPadresSuegrosAuthSocioComponent;
  let fixture: ComponentFixture<TablaBeneficiosPadresSuegrosAuthSocioComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ TablaBeneficiosPadresSuegrosAuthSocioComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(TablaBeneficiosPadresSuegrosAuthSocioComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
