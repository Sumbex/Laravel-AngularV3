import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AsistenciaReunionComponent } from './asistencia-reunion.component';

describe('AsistenciaReunionComponent', () => {
  let component: AsistenciaReunionComponent;
  let fixture: ComponentFixture<AsistenciaReunionComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AsistenciaReunionComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AsistenciaReunionComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
