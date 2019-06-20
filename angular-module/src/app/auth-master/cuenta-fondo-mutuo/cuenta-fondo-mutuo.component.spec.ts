import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { CuentaFondoMutuoComponent } from './cuenta-fondo-mutuo.component';

describe('CuentaFondoMutuoComponent', () => {
  let component: CuentaFondoMutuoComponent;
  let fixture: ComponentFixture<CuentaFondoMutuoComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ CuentaFondoMutuoComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(CuentaFondoMutuoComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
