import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { HistorialDetalleReunionComponent } from './historial-detalle-reunion.component';

describe('HistorialDetalleReunionComponent', () => {
  let component: HistorialDetalleReunionComponent;
  let fixture: ComponentFixture<HistorialDetalleReunionComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ HistorialDetalleReunionComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(HistorialDetalleReunionComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
