import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { TotalCajaChicaComponent } from './total-caja-chica.component';

describe('TotalCajaChicaComponent', () => {
  let component: TotalCajaChicaComponent;
  let fixture: ComponentFixture<TotalCajaChicaComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ TotalCajaChicaComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(TotalCajaChicaComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
