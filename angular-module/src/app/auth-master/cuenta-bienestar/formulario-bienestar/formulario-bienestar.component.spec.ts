import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { FormularioBienestarComponent } from './formulario-bienestar.component';

describe('FormularioBienestarComponent', () => {
  let component: FormularioBienestarComponent;
  let fixture: ComponentFixture<FormularioBienestarComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ FormularioBienestarComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(FormularioBienestarComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
