import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { CajaBienestarSociosComponent } from './caja-bienestar-socios.component';

describe('CajaBienestarSociosComponent', () => {
  let component: CajaBienestarSociosComponent;
  let fixture: ComponentFixture<CajaBienestarSociosComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ CajaBienestarSociosComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(CajaBienestarSociosComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
