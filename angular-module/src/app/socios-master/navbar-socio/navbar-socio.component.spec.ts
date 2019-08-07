import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { NavbarSocioComponent } from './navbar-socio.component';

describe('NavbarSocioComponent', () => {
  let component: NavbarSocioComponent;
  let fixture: ComponentFixture<NavbarSocioComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ NavbarSocioComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(NavbarSocioComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
