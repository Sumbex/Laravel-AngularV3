import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { HistorialLiquidacionesComponent } from './historial-liquidaciones.component';

describe('HistorialLiquidacionesComponent', () => {
  let component: HistorialLiquidacionesComponent;
  let fixture: ComponentFixture<HistorialLiquidacionesComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ HistorialLiquidacionesComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(HistorialLiquidacionesComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
