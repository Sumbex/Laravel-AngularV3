import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { TablaBeneficiosSocioComponent } from './tabla-beneficios-socio.component';

describe('TablaBeneficiosSocioComponent', () => {
  let component: TablaBeneficiosSocioComponent;
  let fixture: ComponentFixture<TablaBeneficiosSocioComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ TablaBeneficiosSocioComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(TablaBeneficiosSocioComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
