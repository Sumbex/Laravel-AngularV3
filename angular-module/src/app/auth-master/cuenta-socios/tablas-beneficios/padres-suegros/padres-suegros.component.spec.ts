import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { PadresSuegrosComponent } from './padres-suegros.component';

describe('PadresSuegrosComponent', () => {
  let component: PadresSuegrosComponent;
  let fixture: ComponentFixture<PadresSuegrosComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ PadresSuegrosComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(PadresSuegrosComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
