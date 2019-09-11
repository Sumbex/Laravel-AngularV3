import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { BeneficiosCobradosComponent } from './beneficios-cobrados.component';

describe('BeneficiosCobradosComponent', () => {
  let component: BeneficiosCobradosComponent;
  let fixture: ComponentFixture<BeneficiosCobradosComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ BeneficiosCobradosComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(BeneficiosCobradosComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
