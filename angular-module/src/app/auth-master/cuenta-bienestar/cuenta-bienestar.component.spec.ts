import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { CuentaBienestarComponent } from './cuenta-bienestar.component';

describe('CuentaBienestarComponent', () => {
  let component: CuentaBienestarComponent;
  let fixture: ComponentFixture<CuentaBienestarComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ CuentaBienestarComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(CuentaBienestarComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
