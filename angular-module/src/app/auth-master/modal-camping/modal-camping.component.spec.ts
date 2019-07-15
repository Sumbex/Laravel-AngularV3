import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ModalCampingComponent } from './modal-camping.component';

describe('ModalCampingComponent', () => {
  let component: ModalCampingComponent;
  let fixture: ComponentFixture<ModalCampingComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ModalCampingComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ModalCampingComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
