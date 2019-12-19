import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { DirectorioSocioComponent } from './directorio-socio.component';

describe('DirectorioSocioComponent', () => {
  let component: DirectorioSocioComponent;
  let fixture: ComponentFixture<DirectorioSocioComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ DirectorioSocioComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(DirectorioSocioComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
