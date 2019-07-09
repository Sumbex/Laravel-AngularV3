import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { TablaCajaChicaComponent } from './tabla-caja-chica.component';

describe('TablaCajaChicaComponent', () => {
  let component: TablaCajaChicaComponent;
  let fixture: ComponentFixture<TablaCajaChicaComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ TablaCajaChicaComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(TablaCajaChicaComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
