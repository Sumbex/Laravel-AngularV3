import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { HaberesComponent } from './haberes.component';

describe('HaberesComponent', () => {
  let component: HaberesComponent;
  let fixture: ComponentFixture<HaberesComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ HaberesComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(HaberesComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
