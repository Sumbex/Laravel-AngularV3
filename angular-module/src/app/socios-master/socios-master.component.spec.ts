import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { SociosMasterComponent } from './socios-master.component';

describe('SociosMasterComponent', () => {
  let component: SociosMasterComponent;
  let fixture: ComponentFixture<SociosMasterComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ SociosMasterComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(SociosMasterComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
