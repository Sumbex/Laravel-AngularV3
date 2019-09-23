import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { CuentaBienestarSociosComponent } from './cuenta-bienestar-socios.component';

describe('CuentaBienestarSociosComponent', () => {
  let component: CuentaBienestarSociosComponent;
  let fixture: ComponentFixture<CuentaBienestarSociosComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ CuentaBienestarSociosComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(CuentaBienestarSociosComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
