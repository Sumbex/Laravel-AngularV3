import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AcuerdosSocioComponent } from './acuerdos-socio.component';

describe('AcuerdosSocioComponent', () => {
  let component: AcuerdosSocioComponent;
  let fixture: ComponentFixture<AcuerdosSocioComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AcuerdosSocioComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AcuerdosSocioComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
