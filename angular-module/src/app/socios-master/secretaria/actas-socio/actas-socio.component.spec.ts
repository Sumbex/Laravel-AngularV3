import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ActasSocioComponent } from './actas-socio.component';

describe('ActasSocioComponent', () => {
  let component: ActasSocioComponent;
  let fixture: ComponentFixture<ActasSocioComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ActasSocioComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ActasSocioComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
