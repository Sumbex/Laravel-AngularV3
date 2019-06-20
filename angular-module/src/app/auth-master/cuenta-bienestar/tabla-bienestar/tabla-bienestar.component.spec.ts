import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { TablaBienestarComponent } from './tabla-bienestar.component';

describe('TablaBienestarComponent', () => {
  let component: TablaBienestarComponent;
  let fixture: ComponentFixture<TablaBienestarComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ TablaBienestarComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(TablaBienestarComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
