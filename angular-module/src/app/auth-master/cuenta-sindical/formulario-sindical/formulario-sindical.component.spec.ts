import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { FormularioSindicalComponent } from './formulario-sindical.component';

describe('FormularioSindicalComponent', () => {
  let component: FormularioSindicalComponent;
  let fixture: ComponentFixture<FormularioSindicalComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ FormularioSindicalComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(FormularioSindicalComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
