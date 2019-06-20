import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { TablaSindicalComponent } from './tabla-sindical.component';

describe('TablaSindicalComponent', () => {
  let component: TablaSindicalComponent;
  let fixture: ComponentFixture<TablaSindicalComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ TablaSindicalComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(TablaSindicalComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
