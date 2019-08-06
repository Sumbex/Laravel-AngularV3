import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { TablaBeneficiosPadresSuegrosComponent } from './tabla-beneficios-padres-suegros.component';

describe('TablaBeneficiosPadresSuegrosComponent', () => {
  let component: TablaBeneficiosPadresSuegrosComponent;
  let fixture: ComponentFixture<TablaBeneficiosPadresSuegrosComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ TablaBeneficiosPadresSuegrosComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(TablaBeneficiosPadresSuegrosComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
