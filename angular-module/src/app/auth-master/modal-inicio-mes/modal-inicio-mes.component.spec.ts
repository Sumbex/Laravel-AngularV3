import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ModalInicioMesComponent } from './modal-inicio-mes.component';

describe('ModalInicioMesComponent', () => {
  let component: ModalInicioMesComponent;
  let fixture: ComponentFixture<ModalInicioMesComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ModalInicioMesComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ModalInicioMesComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
