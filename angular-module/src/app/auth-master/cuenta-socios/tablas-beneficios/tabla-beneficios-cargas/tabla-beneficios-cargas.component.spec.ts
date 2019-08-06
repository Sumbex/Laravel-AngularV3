import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { TablaBeneficiosCargasComponent } from './tabla-beneficios-cargas.component';

describe('TablaBeneficiosCargasComponent', () => {
  let component: TablaBeneficiosCargasComponent;
  let fixture: ComponentFixture<TablaBeneficiosCargasComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ TablaBeneficiosCargasComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(TablaBeneficiosCargasComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
