import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ActividadesDirectorioComponent } from './actividades-directorio.component';

describe('ActividadesDirectorioComponent', () => {
  let component: ActividadesDirectorioComponent;
  let fixture: ComponentFixture<ActividadesDirectorioComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ActividadesDirectorioComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ActividadesDirectorioComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
