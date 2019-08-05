import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { TablaBeneficiosConyugeComponent } from './tabla-beneficios-conyuge.component';

describe('TablaBeneficiosConyugeComponent', () => {
  let component: TablaBeneficiosConyugeComponent;
  let fixture: ComponentFixture<TablaBeneficiosConyugeComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ TablaBeneficiosConyugeComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(TablaBeneficiosConyugeComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
