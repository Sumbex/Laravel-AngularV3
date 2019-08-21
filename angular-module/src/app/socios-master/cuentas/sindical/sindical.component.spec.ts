import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { SindicalComponent } from './sindical.component';

describe('SindicalComponent', () => {
  let component: SindicalComponent;
  let fixture: ComponentFixture<SindicalComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ SindicalComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(SindicalComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
