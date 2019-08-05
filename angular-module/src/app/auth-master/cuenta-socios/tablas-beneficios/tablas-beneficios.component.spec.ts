import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { TablasBeneficiosComponent } from './tablas-beneficios.component';

describe('TablasBeneficiosComponent', () => {
  let component: TablasBeneficiosComponent;
  let fixture: ComponentFixture<TablasBeneficiosComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ TablasBeneficiosComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(TablasBeneficiosComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
