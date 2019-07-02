import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ModalCajaChicaComponent } from './modal-caja-chica.component';

describe('ModalCajaChicaComponent', () => {
  let component: ModalCajaChicaComponent;
  let fixture: ComponentFixture<ModalCajaChicaComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ModalCajaChicaComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ModalCajaChicaComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
