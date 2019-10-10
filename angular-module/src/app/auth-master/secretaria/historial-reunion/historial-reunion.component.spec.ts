import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { HistorialReunionComponent } from './historial-reunion.component';

describe('HistorialReunionComponent', () => {
  let component: HistorialReunionComponent;
  let fixture: ComponentFixture<HistorialReunionComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ HistorialReunionComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(HistorialReunionComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
