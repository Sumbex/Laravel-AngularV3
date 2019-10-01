import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { GeneralReunionComponent } from './general-reunion.component';

describe('GeneralReunionComponent', () => {
  let component: GeneralReunionComponent;
  let fixture: ComponentFixture<GeneralReunionComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ GeneralReunionComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(GeneralReunionComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
