import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { TotalCuentaSindicalComponent } from './total-cuenta-sindical.component';

describe('TotalCuentaSindicalComponent', () => {
  let component: TotalCuentaSindicalComponent;
  let fixture: ComponentFixture<TotalCuentaSindicalComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ TotalCuentaSindicalComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(TotalCuentaSindicalComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
