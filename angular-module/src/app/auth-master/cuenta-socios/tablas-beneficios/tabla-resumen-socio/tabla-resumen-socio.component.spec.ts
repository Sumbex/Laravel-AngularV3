import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { TablaResumenSocioComponent } from './tabla-resumen-socio.component';

describe('TablaResumenSocioComponent', () => {
  let component: TablaResumenSocioComponent;
  let fixture: ComponentFixture<TablaResumenSocioComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ TablaResumenSocioComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(TablaResumenSocioComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
