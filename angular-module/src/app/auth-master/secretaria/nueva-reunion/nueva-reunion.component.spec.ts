import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { NuevaReunionComponent } from './nueva-reunion.component';

describe('NuevaReunionComponent', () => {
  let component: NuevaReunionComponent;
  let fixture: ComponentFixture<NuevaReunionComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ NuevaReunionComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(NuevaReunionComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
