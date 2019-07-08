import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { CuentaSociosComponent } from './cuenta-socios.component';

describe('CuentaSociosComponent', () => {
  let component: CuentaSociosComponent;
  let fixture: ComponentFixture<CuentaSociosComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ CuentaSociosComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(CuentaSociosComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
