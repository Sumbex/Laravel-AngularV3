import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ModalGastosOperacionalesComponent } from './modal-gastos-operacionales.component';

describe('ModalGastosOperacionalesComponent', () => {
  let component: ModalGastosOperacionalesComponent;
  let fixture: ComponentFixture<ModalGastosOperacionalesComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ModalGastosOperacionalesComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ModalGastosOperacionalesComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
