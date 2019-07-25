import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { TablaPrestamosSociosComponent } from './tabla-prestamos-socios.component';

describe('TablaPrestamosSociosComponent', () => {
  let component: TablaPrestamosSociosComponent;
  let fixture: ComponentFixture<TablaPrestamosSociosComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ TablaPrestamosSociosComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(TablaPrestamosSociosComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
