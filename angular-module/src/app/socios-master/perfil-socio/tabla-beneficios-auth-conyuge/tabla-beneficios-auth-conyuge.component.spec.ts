import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { TablaBeneficiosAuthConyugeComponent } from './tabla-beneficios-auth-conyuge.component';

describe('TablaBeneficiosAuthConyugeComponent', () => {
  let component: TablaBeneficiosAuthConyugeComponent;
  let fixture: ComponentFixture<TablaBeneficiosAuthConyugeComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ TablaBeneficiosAuthConyugeComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(TablaBeneficiosAuthConyugeComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
