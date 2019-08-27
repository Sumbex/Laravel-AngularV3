import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { TablaFallecimientoBienestarComponent } from './tabla-fallecimiento-bienestar.component';

describe('TablaFallecimientoBienestarComponent', () => {
  let component: TablaFallecimientoBienestarComponent;
  let fixture: ComponentFixture<TablaFallecimientoBienestarComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ TablaFallecimientoBienestarComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(TablaFallecimientoBienestarComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
