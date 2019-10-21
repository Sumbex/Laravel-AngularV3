import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { EditarReunionComponent } from './editar-reunion.component';

describe('EditarReunionComponent', () => {
  let component: EditarReunionComponent;
  let fixture: ComponentFixture<EditarReunionComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ EditarReunionComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(EditarReunionComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
