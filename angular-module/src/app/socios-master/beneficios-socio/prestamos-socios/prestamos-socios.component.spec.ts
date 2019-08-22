import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { PrestamosSociosComponent } from './prestamos-socios.component';

describe('PrestamosSociosComponent', () => {
  let component: PrestamosSociosComponent;
  let fixture: ComponentFixture<PrestamosSociosComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ PrestamosSociosComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(PrestamosSociosComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
