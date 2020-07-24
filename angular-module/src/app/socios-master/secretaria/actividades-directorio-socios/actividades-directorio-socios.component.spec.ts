import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ActividadesDirectorioSociosComponent } from './actividades-directorio-socios.component';

describe('ActividadesDirectorioSociosComponent', () => {
  let component: ActividadesDirectorioSociosComponent;
  let fixture: ComponentFixture<ActividadesDirectorioSociosComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ActividadesDirectorioSociosComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ActividadesDirectorioSociosComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
