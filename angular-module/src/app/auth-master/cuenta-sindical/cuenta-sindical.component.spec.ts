import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { CuentaSindicalComponent } from './cuenta-sindical.component';

describe('CuentaSindicalComponent', () => {
  let component: CuentaSindicalComponent;
  let fixture: ComponentFixture<CuentaSindicalComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ CuentaSindicalComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(CuentaSindicalComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
