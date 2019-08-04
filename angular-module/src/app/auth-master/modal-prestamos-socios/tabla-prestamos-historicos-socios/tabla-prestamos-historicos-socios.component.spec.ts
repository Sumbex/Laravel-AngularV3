import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { TablaPrestamosHistoricosSociosComponent } from './tabla-prestamos-historicos-socios.component';

describe('TablaPrestamosHistoricosSociosComponent', () => {
  let component: TablaPrestamosHistoricosSociosComponent;
  let fixture: ComponentFixture<TablaPrestamosHistoricosSociosComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ TablaPrestamosHistoricosSociosComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(TablaPrestamosHistoricosSociosComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
