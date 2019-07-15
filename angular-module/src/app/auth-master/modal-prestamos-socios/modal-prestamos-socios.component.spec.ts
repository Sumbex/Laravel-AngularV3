import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ModalPrestamosSociosComponent } from './modal-prestamos-socios.component';

describe('ModalPrestamosSociosComponent', () => {
  let component: ModalPrestamosSociosComponent;
  let fixture: ComponentFixture<ModalPrestamosSociosComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ModalPrestamosSociosComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ModalPrestamosSociosComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
