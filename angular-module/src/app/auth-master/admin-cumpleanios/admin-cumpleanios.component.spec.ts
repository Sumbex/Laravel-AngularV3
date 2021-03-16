import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AdminCumpleaniosComponent } from './admin-cumpleanios.component';

describe('AdminCumpleaniosComponent', () => {
  let component: AdminCumpleaniosComponent;
  let fixture: ComponentFixture<AdminCumpleaniosComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AdminCumpleaniosComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AdminCumpleaniosComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
