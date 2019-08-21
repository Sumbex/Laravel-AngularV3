import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { BeneficiosSocioComponent } from './beneficios-socio.component';

describe('BeneficiosSocioComponent', () => {
  let component: BeneficiosSocioComponent;
  let fixture: ComponentFixture<BeneficiosSocioComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ BeneficiosSocioComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(BeneficiosSocioComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
