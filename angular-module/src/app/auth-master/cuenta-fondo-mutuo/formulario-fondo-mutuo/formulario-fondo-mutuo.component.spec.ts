import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { FormularioFondoMutuoComponent } from './formulario-fondo-mutuo.component';

describe('FormularioFondoMutuoComponent', () => {
  let component: FormularioFondoMutuoComponent;
  let fixture: ComponentFixture<FormularioFondoMutuoComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ FormularioFondoMutuoComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(FormularioFondoMutuoComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
