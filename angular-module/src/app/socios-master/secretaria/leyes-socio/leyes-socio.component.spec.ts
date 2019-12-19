import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { LeyesSocioComponent } from './leyes-socio.component';

describe('LeyesSocioComponent', () => {
  let component: LeyesSocioComponent;
  let fixture: ComponentFixture<LeyesSocioComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ LeyesSocioComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(LeyesSocioComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
