import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { BeneficiosSociosComponent } from './beneficios-socios.component';

describe('BeneficiosSociosComponent', () => {
  let component: BeneficiosSociosComponent;
  let fixture: ComponentFixture<BeneficiosSociosComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ BeneficiosSociosComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(BeneficiosSociosComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
