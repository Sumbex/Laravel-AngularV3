import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { TablaFondoMutuoComponent } from './tabla-fondo-mutuo.component';

describe('TablaFondoMutuoComponent', () => {
  let component: TablaFondoMutuoComponent;
  let fixture: ComponentFixture<TablaFondoMutuoComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ TablaFondoMutuoComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(TablaFondoMutuoComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
