import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { CambioContraseniaSocioComponent } from './cambio-contrasenia-socio.component';

describe('CambioContraseniaSocioComponent', () => {
  let component: CambioContraseniaSocioComponent;
  let fixture: ComponentFixture<CambioContraseniaSocioComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ CambioContraseniaSocioComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(CambioContraseniaSocioComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
