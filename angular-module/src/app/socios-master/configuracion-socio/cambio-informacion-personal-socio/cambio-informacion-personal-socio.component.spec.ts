import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { CambioInformacionPersonalSocioComponent } from './cambio-informacion-personal-socio.component';

describe('CambioInformacionPersonalSocioComponent', () => {
  let component: CambioInformacionPersonalSocioComponent;
  let fixture: ComponentFixture<CambioInformacionPersonalSocioComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ CambioInformacionPersonalSocioComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(CambioInformacionPersonalSocioComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
