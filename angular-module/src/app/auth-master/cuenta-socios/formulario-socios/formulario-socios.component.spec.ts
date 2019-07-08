import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { FormularioSociosComponent } from './formulario-socios.component';

describe('FormularioSociosComponent', () => {
  let component: FormularioSociosComponent;
  let fixture: ComponentFixture<FormularioSociosComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ FormularioSociosComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(FormularioSociosComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
