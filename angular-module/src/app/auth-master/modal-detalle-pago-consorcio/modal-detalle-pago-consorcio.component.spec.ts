import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ModalDetallePagoConsorcioComponent } from './modal-detalle-pago-consorcio.component';

describe('ModalDetallePagoConsorcioComponent', () => {
  let component: ModalDetallePagoConsorcioComponent;
  let fixture: ComponentFixture<ModalDetallePagoConsorcioComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ModalDetallePagoConsorcioComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ModalDetallePagoConsorcioComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
