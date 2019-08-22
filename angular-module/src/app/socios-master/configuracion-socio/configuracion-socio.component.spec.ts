import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ConfiguracionSocioComponent } from './configuracion-socio.component';

describe('ConfiguracionSocioComponent', () => {
  let component: ConfiguracionSocioComponent;
  let fixture: ComponentFixture<ConfiguracionSocioComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ConfiguracionSocioComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ConfiguracionSocioComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
