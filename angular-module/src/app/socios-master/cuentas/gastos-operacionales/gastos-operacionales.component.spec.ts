import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { GastosOperacionalesComponent } from './gastos-operacionales.component';

describe('GastosOperacionalesComponent', () => {
  let component: GastosOperacionalesComponent;
  let fixture: ComponentFixture<GastosOperacionalesComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ GastosOperacionalesComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(GastosOperacionalesComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
