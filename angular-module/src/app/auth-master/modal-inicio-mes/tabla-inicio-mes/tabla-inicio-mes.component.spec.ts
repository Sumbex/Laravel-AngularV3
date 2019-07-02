import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { TablaInicioMesComponent } from './tabla-inicio-mes.component';

describe('TablaInicioMesComponent', () => {
  let component: TablaInicioMesComponent;
  let fixture: ComponentFixture<TablaInicioMesComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ TablaInicioMesComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(TablaInicioMesComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
